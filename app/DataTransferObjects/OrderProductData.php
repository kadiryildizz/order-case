<?php

namespace App\DataTransferObjects;


class OrderProductData extends AbstractDataTransferObject
{
    protected ?int $productId = null;
    protected ?int $categoryId = null;
    protected ?int $orderId = null;
    protected ?int $quantity = null;
    protected ?float $unitPrice = null;
    protected ?float $discount = null;
    protected ?float $totalPrice = null;

    public static function builder(): static
    {
        return new static();
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
    public function setProductId(?int $productId): OrderProductData
    {
        $this->productId = $productId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @param int|null $categoryId
     */
    public function setCategoryId(?int $categoryId): OrderProductData
    {
        $this->categoryId = $categoryId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    /**
     * @param int|null $orderId
     */
    public function setOrderId(?int $orderId): OrderProductData
    {
        $this->orderId = $orderId;
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
    public function setQuantity(?int $quantity): OrderProductData
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    /**
     * @param float|null $unitPrice
     */
    public function setUnitPrice(?float $unitPrice): OrderProductData
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    /**
     * @param float|null $discount
     */
    public function setDiscount(?float $discount): OrderProductData
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    /**
     * @param float|null $totalPrice
     */
    public function setTotalPrice(?float $totalPrice): OrderProductData
    {
        $this->totalPrice = $totalPrice;
        return $this;
    }

}
