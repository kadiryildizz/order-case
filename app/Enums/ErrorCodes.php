<?php

namespace App\Enums;

class ErrorCodes extends Enum
{
    const VALIDATION_PRODUCT_EXISTS = [
        'code' => 5001,
        'message' => "product not exist",
    ];
    const VALIDATION_PRODUCT_STOCK_EXISTS  = [
        'code' => 5002,
        'message' => "stock not availability",
    ];

    const VALIDATION_CAMPAIGN_TIME_RANGE_EXISTS  = [
        'code' => 5002,
        'message' => "Time range invalid for campaign",
    ];


}
