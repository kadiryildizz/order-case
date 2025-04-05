<?php

namespace App\Http\Controllers\Api;

use App\DataTransferObjects\Basket\BasketData;
use App\DataTransferObjects\OrderData;
use App\Enums\ErrorCodes;
use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Exceptions\BasketOrderException;
use App\Http\Controllers\Controller;
use App\Http\Requests\BasketOrderAddRequest;
use App\Http\Requests\BasketPreviewRequest;
use App\Http\Requests\CampaignUpdateRequest;
use App\Http\Resources\Order\OrderListResource;
use App\Libraries\Responder\Responder;
use App\Models\Order;
use App\Models\Product;
use App\Services\BasketOrder\BasketOrderService;
use App\Services\Order\OrderService;
use Illuminate\Http\JsonResponse;

class BasketOrderController extends Controller
{
    /**
     * Add Basket Order
     * @param BasketOrderAddRequest $request
     * @return JsonResponse
     * @throws BasketOrderException
     */
    public function add(BasketOrderAddRequest $request): JsonResponse
    {
        $createBasketOrder = $request->validated();
        $product = Product::where('status', ProductStatus::ACTIVE)
            ->where('id', $createBasketOrder["product_id"])->first();
        if(empty($product)){
            throw new BasketOrderException(
                message: ErrorCodes::VALIDATION_PRODUCT_EXISTS['message'],
                statusCode: 400,
            );
        }
        if ($product->stock < $createBasketOrder["quantity"]) {
            throw new BasketOrderException(
                message: ErrorCodes::VALIDATION_PRODUCT_STOCK_EXISTS['message'],
                statusCode: 400,
            );
        }

        $basketData = BasketData::builder()
            ->setProduct($product)
            ->setProductId($createBasketOrder["product_id"])
            ->setQuantity($createBasketOrder["quantity"])
            ->setCustomerId($createBasketOrder["customer_id"])
            ->setCampaignId($createBasketOrder["campaign_id"] ?? null);

        $data = (new BasketOrderService())->add($basketData);
        return Responder::success(
            [
                OrderListResource::make($data)
            ]
        );
    }

    /**
     * Preview Basket
     * @param BasketPreviewRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function preview(BasketPreviewRequest $request): JsonResponse
    {
        $previewBasket = $request->validated();

        $basketData = BasketData::builder()
            ->setCustomerId($previewBasket["customer_id"]);

        $data = (new BasketOrderService())->preview($basketData);
        return Responder::success(
            [
                OrderListResource::make($data)
            ]
        );
    }

    /**
     * Campaign Apply
     * @param $id
     * @param CampaignUpdateRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function campaignApply(Order $order, CampaignUpdateRequest $request,OrderData $orderData): JsonResponse
    {
        $campaignUpdate = $request->validated();
        $id = $order->id;
        $orderData->setId($id);
        $orderData->setCampaignId($campaignUpdate["campaign_id"]);
        $data = (new OrderService())->updateCampaign($orderData);
        return Responder::success(
            [
                OrderListResource::make($data)
            ]
        );
    }

    /**
     * Canceled Basket
     * @param $id
     * @return JsonResponse
     * @throws Throwable
     */
    public function canceled(Order $order): JsonResponse
    {
        $orderData = OrderData::builder()
            ->setId($order->id)
            ->setStatus(OrderStatus::CANCELLED);
        (new OrderService())->updateStatus($orderData);

        return Responder::success();
    }
}
