<?php

namespace App\DataTransferObjects;


class OrderData extends AbstractDataTransferObject
{
    protected ?int $id = null;
    protected ?int $customerId = null;
    protected ?int $status = null;
    protected ?int $campaignId = null;
    protected ?CampaignData $campaing = null;
    protected ?float $total = null;
    protected ?array $items = null;

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
    public function setId(?int $id): OrderData
    {
        $this->id = $id;
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
    public function setCustomerId(?int $customerId): OrderData
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): OrderData
    {
        $this->status = $status;
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
    public function setCampaignId(?int $campaignId): OrderData
    {
        $this->campaignId = $campaignId;
        return $this;
    }

    /**
     * @return CampaignData|null
     */
    public function getCampaing(): ?CampaignData
    {
        return $this->campaing;
    }

    /**
     * @param CampaignData|null $campaing
     */
    public function setCampaing(?CampaignData $campaing): OrderData
    {
        $this->campaing = $campaing;
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
    public function setTotal(?float $total): OrderData
    {
        $this->total = $total;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getItems(): ?array
    {
        return $this->items;
    }

    /**
     * @param array|null $items
     */
    public function setItems(?array $items): OrderData
    {
        $this->items = $items;
        return $this;
    }

}
