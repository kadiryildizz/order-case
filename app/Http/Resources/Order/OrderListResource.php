<?php

namespace App\Http\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->getId(),
            'customer_id'  => $this->getCustomerId(),
            'status'       => $this->getStatus(),
            'campaign_id'  => $this->getCampaignId(),
            'campaing'     => $this->getCampaing() ? [
                'id'       => $this->getCampaing()->getId(),
                'discount' => round($this->getCampaing()->getDiscount(), 2),
                'total'    => round($this->getCampaing()->getTotal(), 2),
                'title'    => $this->getCampaing()->getTitle(),
            ] : null,
            'total'        => round($this->getTotal(), 2),
            'items'        => collect($this->getItems())->map(function ($item) {
                return [
                    'product_id'   => $item->getProductId(),
                    'category_id'  => $item->getCategoryId(),
                    'order_id'     => $item->getOrderId(),
                    'quantity'     => $item->getQuantity(),
                    'unit_price'   => round($item->getUnitPrice(), 2),
                    'discount'     => round($item->getDiscount(), 2),
                    'total_price'  => round($item->getTotalPrice(), 2),
                ];
            })->toArray(),
        ];
    }
}
