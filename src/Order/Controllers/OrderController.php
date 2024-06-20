<?php

namespace Module\Order\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;
use Module\Order\Controllers\Requests\StoreOrderRequest;
use Module\Order\Exceptions\OrderNotFoundException;
use Module\Order\Models\Order;
use Module\Order\Models\OrderItem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): JsonResponse|JsonResource
    {
        try {
            $orderDb = DB::transaction(function () use ($request) {
                $total = array_reduce($request->validated('items'), function ($total, $item) {
                    $total += $item['quantity'] * $item['price_in_cents'];
                    return $total;
                }, 0);
                $orderDb = Order::create([
                    'customer_id' => $request->validated('customer_id'),
                    'amount_in_cents' => $total,
                ]);
                foreach ($request->validated('items') as $item) {
                    OrderItem::create([
                        'order_id' => $orderDb->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'amount_in_cents' => $item['quantity'] * $item['price_in_cents'],
                    ]);
                }
                return $orderDb;
            });
            return new OrderResource($orderDb);
        } catch (\Throwable $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function index(): JsonResponse|JsonResource
    {
        try {
            $orders = Order::get();
            return OrderResource::collection($orders);
        } catch (\Throwable $th) {
            return new JsonResponse(['error' => $th->getMessage()]);
        }
    }

    public function show(int $id): JsonResponse|JsonResource
    {
        try {
            $order = Order::find($id);
            if (!$order) {
                throw new OrderNotFoundException();
            }
            return new OrderResource($order);
        } catch (OrderNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return new JsonResponse(['error' => $th->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $order = Order::find($id);
            if (!$order) {
                throw new OrderNotFoundException();
            }
            $order->delete();
            return new JsonResponse(null, Response::HTTP_NO_CONTENT);
        } catch (OrderNotFoundException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}