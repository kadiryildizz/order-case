<?php

namespace App\Services\Order;

use App\DataTransferObjects\Basket\BasketData;
use App\DataTransferObjects\OrderData;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\BasketOrder\BasketOrderService;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderService
{
    /**
     * @param OrderData $orderData
     * @return OrderData
     * @throws Throwable
     */
    public function findOrCreate(OrderData $orderData): OrderData
    {
        try {
            $order = Order::firstOrCreate(
                [
                    'customer_id' => $orderData->getCustomerId(),
                    'status' => OrderStatus::DRAFT
                ],
                [
                    'customer_id' => $orderData->getCustomerId(),
                    'status' => OrderStatus::DRAFT
                ]
            );
            $orderData->setId($order->id);
            $orderData->setCampaignId($order->campaign_id);
            $orderData->setTotal($order->total);
            return $orderData;
        } catch (\Throwable $th) {
            Log::error('OrderDataFindOrCreateError', [
                'exception' => $th,
            ]);

            throw $th;
        }
    }

    /**
     * @param OrderData $orderData
     * @return mixed[]
     * @throws Throwable
     */
    public function updateCampaign(OrderData $orderData): OrderData
    {
        try {
            $order = Order::where('id', $orderData->getId())->where('status', OrderStatus::DRAFT)->first();
            if (!empty($order)) {
                $order->campaign_id = $orderData->getCampaignId();
                $order->save();
            }else{
                throw new \Exception("Active Order could not be found!");
            }
            $orderData->setCustomerId($order->customer_id);
            $basketData = new BasketData();
            $basketData->setCustomerId($order->customer_id);
            return (new BasketOrderService())->preview($basketData);
        } catch (\Throwable $th) {
            Log::error('OrderUpdateStatusError', [
                'exception' => $th,
            ]);

            throw $th;
        }
    }

    /**
     * @param OrderData $orderData
     * @throws Throwable
     */
    public function updateStatus(OrderData $orderData)
    {
        try {
            $order = Order::where('id', $orderData->getId())->where('status', OrderStatus::DRAFT)->first();
            if (!empty($order)) {
                $order->status = $orderData->getStatus();
                $order->save();
            }
            return;
        } catch (\Throwable $th) {
            Log::error('OrderUpdateStatusError', [
                'exception' => $th,
            ]);

            throw $th;
        }
    }
}
