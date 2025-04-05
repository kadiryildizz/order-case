<?php

namespace App\Enums;

class OrderStatus extends Enum
{
    public const DRAFT = 0;
    public const APPROVED = 1;
    public const CANCELLED = 2;
}