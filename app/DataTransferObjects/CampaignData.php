<?php

namespace App\DataTransferObjects;
class CampaignData extends AbstractDataTransferObject
{
    protected ?int $id = null;
    protected ?float $discount = 0;
    protected ?float $total = 0;
    protected ?string $title = null;

    public static function builder(): static
    {
        return new static();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): CampaignData
    {
        $this->id = $id;
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
    public function setDiscount(?float $discount): CampaignData
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * @param float|null $total
     */
    public function setTotal(?float $total): CampaignData
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): CampaignData
    {
        $this->title = $title;
        return $this;
    }

}
