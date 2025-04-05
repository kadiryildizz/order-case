<?php

namespace App\Services\Order;

use App\DataTransferObjects\OrderData;
use App\DataTransferObjects\OrderProductData;
use App\Enums\ErrorCodes;
use App\Enums\ProductStatus;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Services\Campaign\CampaignService;
use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;

class OrderProductService
{
    /**
     * @param OrderData $orderData
     * @return OrderData
     * @throws Exception
     */
    public function all(OrderData $orderData): OrderData
    {
        $orderProduct = OrderProduct::where('order_id', $orderData->getId())->with('product')->get();
        $orderData->setTotal($orderProduct->sum('total_price'));
        $orderData->setItems($orderProduct->map(function ($item) {
            $orderProductData = OrderProductData::builder()
            ->setProductId($item->product_id)
            ->setCategoryId($item->product->category_id)
            ->setOrderId($item->order_id)
            ->setQuantity($item->quantity)
            ->setUnitPrice($item->unit_price)
            ->setDiscount($item->discount)
            ->setTotalPrice($item->total_price);

            return $orderProductData;

        })->toArray());
        $campaignId = $orderData->getCampaignId();
        if ($campaignId) {
            $orderData = (new CampaignService($orderData))->apply();
        }
        return $orderData;

    }

    /**
     * @param OrderProductData $orderProductData
     * @return OrderProductData
     * @throws Throwable
     */
    public function findCreateOrUpdate(OrderProductData $orderProductData): OrderProductData
    {
        try {
            $order = OrderProduct::where('product_id', $orderProductData->getProductId())
                ->where('order_id', $orderProductData->getOrderId())
                ->first();
            if (!empty($order)) {
                $orderProductData->setQuantity($order->quantity + $orderProductData->getQuantity());
                $orderProductData->setTotalPrice((($orderProductData->getQuantity() * $orderProductData->getUnitPrice()) - $orderProductData->getDiscount()));
            }
            $productStockCheck = Product::where('id', $orderProductData->getProductId())->where('status', ProductStatus::ACTIVE)->where('stock', '>=', $orderProductData->getQuantity())->exists();
            if (!$productStockCheck) {
                throw new Exception(ErrorCodes::VALIDATION_PRODUCT_STOCK_EXISTS['message'], ErrorCodes::VALIDATION_PRODUCT_STOCK_EXISTS['code']);
            }
            if($orderProductData->getQuantity() < 1){
                OrderProduct::where('product_id',$orderProductData->getProductId())->where('order_id',$orderProductData->getOrderId())->delete();
                return $orderProductData;
            }
            $order = OrderProduct::updateOrCreate(
                [
                    'product_id' => $orderProductData->getProductId(),
                    'order_id' => $orderProductData->getOrderId(),
                ],
                [
                    'product_id' => $orderProductData->getProductId(),
                    'order_id' => $orderProductData->getOrderId(),
                    'quantity' => $orderProductData->getQuantity(),
                    'unit_price' => $orderProductData->getUnitPrice(),
                    'discount' => $orderProductData->getDiscount(),
                    'total_price' => $orderProductData->getTotalPrice(),
                ]
            );
            return $orderProductData;
        } catch (\Throwable $th) {
            Log::error('OrderProductDataUpdateOrCreateError', [
                'exception' => $th,
            ]);

            throw $th;
        }
    }
}
