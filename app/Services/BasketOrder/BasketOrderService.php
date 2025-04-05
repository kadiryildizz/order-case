<?php

namespace App\Services\BasketOrder;

use App\DataTransferObjects\Basket\BasketData;
use App\DataTransferObjects\OrderData;
use App\DataTransferObjects\OrderProductData;
use App\Enums\OrderStatus;
use App\Services\Order\OrderProductService;
use App\Services\Order\OrderService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class BasketOrderService
{
    /**
     * @param BasketData $basketData
     * @return array<string, mixed>
     * @throws Throwable
     */
    public function add(BasketData $basketData): OrderData
    {
        try {
            DB::beginTransaction();
            $orderData = OrderData::builder()
                ->setCustomerId($basketData->getCustomerId())
                ->setStatus(OrderStatus::DRAFT)
                ->setCampaignId($basketData->getCampaignId());
            $order = (new OrderService())->findOrCreate($orderData);

            if (!$order) {
                throw new \Exception("Order could not be created or found!");
            }
            $orderProductData = OrderProductData::builder()
                ->setProductId($basketData->getProduct()->id)
                ->setCategoryId($basketData->getProduct()->category_id)
                ->setOrderId($order->getId())
                ->setQuantity($basketData->getQuantity())
                ->setUnitPrice($basketData->getProduct()->price)
                ->setDiscount(0)
                ->setTotalPrice($basketData->getProduct()->price * $basketData->getQuantity());

            $orderProductService = (new OrderProductService());
            $orderProductService->findCreateOrUpdate($orderProductData);
            $orderData = $orderProductService->all($order);
            DB::commit();
            return $orderData;
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error('AddBasketError', [
                'exception' => $th,
            ]);

            throw $th;
        }
    }

    /**
     * @param BasketData $basketData
     * @return array<string, mixed>
     * @throws Throwable
     */
    public function preview(BasketData $basketData): OrderData
    {
        try {
            $orderData = new OrderData();
            $orderData->setCustomerId($basketData->getCustomerId());
            $orderData->setStatus(OrderStatus::DRAFT);
            $order = (new OrderService())->findOrCreate($orderData);
            $orderProductService = (new OrderProductService());
            return $orderProductService->all($order);
        } catch (\Throwable $th) {
            Log::error('PreviewBasketError', [
                'exception' => $th,
            ]);

            throw $th;
        }
    }
}
