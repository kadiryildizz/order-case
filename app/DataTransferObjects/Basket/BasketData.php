<?php

namespace App\DataTransferObjects\Basket;

use App\DataTransferObjects\AbstractDataTransferObject;

class BasketData extends AbstractDataTransferObject
{
    protected ?object $product = null;
    protected ?int $productId = null;
    protected ?int $quantity = null;
    protected ?int $customerId = null;
    protected ?int $campaignId = null;


    public static function builder(): static
    {
        return new static();
    }

    /**
     * @return object|null
     */
    public function getProduct(): ?object
    {
        return $this->product;
    }

    /**
     * @param object|null $product
     */
    public function setProduct(?object $product): BasketData
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getProductId(): ?int
    {
        return $this->productId;
    }

    /**
     * @param int|null $productId
     */
    public function setProductId(?int $productId): BasketData
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    /**
     * @param int|null $quantity
     */
    public function setQuantity(?int $quantity): BasketData
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    /**
     * @param int|null $customerId
     */
    public function setCustomerId(?int $customerId): BasketData
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCampaignId(): ?int
    {
        return $this->campaignId;
    }

    /**
     * @param int|null $campaignId
     */
    public function setCampaignId(?int $campaignId): BasketData
    {
        $this->campaignId = $campaignId;
        return $this;
    }



}
