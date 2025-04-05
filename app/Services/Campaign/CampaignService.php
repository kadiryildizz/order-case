<?php

namespace App\Services\Campaign;

use App\DataTransferObjects\CampaignData;
use App\DataTransferObjects\OrderData;
use App\Enums\CampaignCountApplies;
use App\Enums\CampaignCountTypes;
use App\Enums\CampaignDiscountTypes;
use App\Enums\CampaignStatus;
use App\Enums\ErrorCodes;
use App\Models\Campaign;
use Exception;


class CampaignService
{
    /**
     * @var Campaign
     * @var OrderData
     * @var string
     * @var bool
     */
    private Campaign $campaign;
    private OrderData $orderData;
    private string $dateNow;
    private bool $check = false;

    /**
     * @param OrderData $orderData
     * @throws Exception
     */
    public function __construct(OrderData $orderData)
    {
        $this->orderData = $orderData;
        $this->dateNow = now()->format('Y-m-d H:i:s');
        $this->campaign = Campaign::where('id', $orderData->getCampaignId())->where('status', CampaignStatus::ACTIVE)->first();
        if (!empty($this->campaign)) {
            $this->getCampaign();
            $this->campaign->criterions = json_decode($this->campaign->criterions);
            $this->campaign->criterions = $this->movetoEnd();
            if (isset($this->campaign->criterions) && !empty($this->campaign->criterions)) {
                $campaignData = CampaignData::builder()
                ->setId($this->campaign->id)
                ->setTitle($this->campaign->title);
                $this->orderData->setCampaing($campaignData);
                $check = 0;
                foreach ($this->campaign->criterions as $criterion) {
                    $callableFunctionName = $criterion;
                    if (method_exists($this, $callableFunctionName)) {
                        $check += $this->$callableFunctionName();
                    }
                }
                if ($check == count($this->campaign->criterions)) {
                    $this->check = true;
                }
            }
        }
    }

    /**
     * @return OrderData $orderData
     */
    public function apply(): OrderData
    {
        if ($this->check) {
            $total = $this->orderData->getTotal() - $this->orderData->getCampaing()->getDiscount();
            $setTotal =  $total > 0 ? $total : $this->orderData->getTotal();
            $this->orderData->getCampaing()->setTotal($setTotal);
        }
        return $this->orderData;
    }

    /**
     * @throws Exception
     */
    private function getCampaign()
    {
        if (!empty($this->campaign['active_since']) && $this->campaign['active_since'] > $this->dateNow) {
            throw new Exception(ErrorCodes::VALIDATION_CAMPAIGN_TIME_RANGE_EXISTS['message'], ErrorCodes::VALIDATION_CAMPAIGN_TIME_RANGE_EXISTS['code']);
        }
        if (!empty($this->campaign['active_till']) && $this->campaign['active_till'] < $this->dateNow) {
            throw new Exception(ErrorCodes::VALIDATION_CAMPAIGN_TIME_RANGE_EXISTS['message'], ErrorCodes::VALIDATION_CAMPAIGN_TIME_RANGE_EXISTS['code']);
        }
    }

    /**
     * product_count must be the last running method so it is moved to the last
     * @return mixed
     */
    private function movetoEnd(): mixed
    {

        $array = $this->campaign->criterions;
        if (in_array('product_count', $array)) {
            $index = array_search('product_count', $array);
            unset($array[$index]);
            array_push($array, 'product_count');
        }
        return $array;
    }

    /**
     * Minimum basket amount check
     * @return int
     */
    private function minimum_cart_total()
    {
        $isCardTotal = $this->orderData->getTotal() >= $this->campaign->minimum_cart_total ? 1 : 0;
        if ($isCardTotal){
            $this->useNotCriterion();
        }
        return $isCardTotal;
    }


    /**
     * In combination campaigns, apply if there is no criteria and combination in the combination.
     */
    private function useNotCriterion()
    {
        if (empty(array_intersect(['combine'], $this->campaign->criterions))) {
            $this->basketDiscount();
        }
    }

    /**
     * Cart Discount
     */
    private function basketDiscount()
    {
        if ($this->campaign->discount_type == CampaignDiscountTypes::PERCENT) {
            $setDiscountData = ($this->orderData->getTotal() * $this->campaign->discount) / 100;
            $this->orderData->getCampaing()->setDiscount($setDiscountData);
        } else {
            $discount = $this->campaign->discount;
            $this->orderData->getCampaing()->setDiscount($discount);
        }
    }

    /**
     * combinations are called
     * @return bool|void
     */
    private function combine()
    {
        $callableFunctionName = sprintf('%s_%s', "combine", $this->campaign->main_type);
        if (method_exists($this, $callableFunctionName)) {
            $this->$callableFunctionName();
            return true;
        }
    }

    private function combine_PRODUCT()
    {
    }

    private function combine_BRAND()
    {
    }

    /**
     * category combination
     */
    private function combine_CATEGORY()
    {
        if ($this->campaign->count_type === CampaignCountTypes::DIFF) {
            $this->discountCombineDiff();
        } else if ($this->campaign->count_type === CampaignCountTypes::SAME) {
            $this->discountCombineSame();
        }

    }

    /**
     * apply discount for different items in cart
     * CHEAPEST,PREVIOUS
     */
    private function discountCombineDiff()
    {
        $filter = collect($this->orderData->getItems())->map(function ($item) {
            return $item->toArray();
        });
        if ($filter->where('categoryId', $this->campaign->main_id)->count() >= $this->campaign->product_count) {
            if ($this->campaign->count_apply == CampaignCountApplies::CHEAPEST) {
                $cheapestProduct = $filter->sortBy('unitPrice')->first();
                $this->orderData->setItems(
                    collect($this->orderData->getItems())->map(function ($item) use ($cheapestProduct) {
                        if ($item->getProductId() == $cheapestProduct['productId'] && $this->campaign->count_type == CampaignCountTypes::DIFF) {
                            $item = $this->discountCombine($item);
                            return $item;
                        }
                        return $item;
                    })->toArray()
                );
            }
        }
    }

    /**
     * apply a discount for the same items in the cart
     */
    private function discountCombineSame()
    {
        $this->orderData->setItems(
            collect($this->orderData->getItems())->map(function ($item) {
                if ($item->getCategoryId() == $this->campaign->main_id) {
                    if ($this->campaign->count_type == CampaignCountTypes::SAME) {
                        if ($item->getQuantity() >= $this->campaign->product_count) {
                            $item = $this->discountCombine($item);
                        }
                    }
                }
                return $item;
            })->toArray()
        );
    }

    /**
     * discount by discount type
     * @param $item
     * @return mixed
     */
    private function discountCombine($item): mixed
    {
        if ($this->campaign->discount_type == CampaignDiscountTypes::GIFT) {
            $setItemDiscount = $this->campaign->discount * $item->getUnitPrice();
            $item->setDiscount($setItemDiscount);

            $setDiscount =  $this->orderData->getCampaing()->getDiscount() + $item->getDiscount();
            $this->orderData->getCampaing()->setDiscount($setDiscount);
        } else if ($this->campaign->discount_type == CampaignDiscountTypes::PERCENT) {
            $setItemDiscount = ($item->getUnitPrice() * $this->campaign->discount) / 100;
            $item->setDiscount($setItemDiscount);

            $setDiscount =  $this->orderData->getCampaing()->getDiscount() + $item->getDiscount();
            $this->orderData->getCampaing()->setDiscount($setDiscount);
        } else if ($this->campaign->discount_type == CampaignDiscountTypes::AMOUNT) {

            $setItemDiscount = $this->campaign->discount;
            $item->setDiscount($setItemDiscount);
            $setDiscount =  $this->orderData->getCampaing()->getDiscount() + $item->getDiscount();
            $this->orderData->getCampaing()->setDiscount($setDiscount);
        }
        return $item;
    }
}
