<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return OrderResource::collection(
            Order::query()
                ->with(['items.product', 'customer', 'serviceTeam.leader', 'serviceTeam.members', 'handledBy'])
                ->orderByDesc('order_date')
                ->get()
        );
    }

    public function store(StoreOrderRequest $request): OrderResource
    {
        $validated = $request->validated();
        $items = $validated['items'] ?? [];
        unset($validated['items']);

        $order = DB::transaction(function () use ($validated, $items) {
            $order = Order::create($validated);

            if (!empty($items)) {
                $lineItems = $this->buildOrderItems($items);
                $order->items()->createMany($lineItems);
                $order->update([
                    'total_amount' => collect($lineItems)->sum('line_total'),
                ]);
            }

            return $order;
        });

        $order->load(['items.product', 'customer', 'serviceTeam.leader', 'serviceTeam.members', 'handledBy']);

        return new OrderResource($order);
    }

    public function show(Order $order): OrderResource
    {
        $order->load(['items.product', 'customer', 'serviceTeam.leader', 'serviceTeam.members', 'handledBy']);

        return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order): OrderResource
    {
        $validated = $request->validated();
        $items = $validated['items'] ?? null;
        unset($validated['items']);

        DB::transaction(function () use ($order, $validated, $items) {
            $order->update($validated);

            if (is_array($items)) {
                $lineItems = $this->buildOrderItems($items);
                $order->items()->delete();
                $order->items()->createMany($lineItems);
                $order->update([
                    'total_amount' => collect($lineItems)->sum('line_total'),
                ]);
            }
        });

        $order->load(['items.product', 'customer', 'serviceTeam.leader', 'serviceTeam.members', 'handledBy']);

        return new OrderResource($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return Response::json([], 204);
    }

    private function buildOrderItems(array $items): array
    {
        $productIds = collect($items)->pluck('product_id')->unique()->all();
        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        return collect($items)->map(function (array $item) use ($products) {
            $product = $products->get($item['product_id']);
            $unitPrice = $item['unit_price'] ?? ($product?->price ?? 0);
            $quantity = (int) $item['quantity'];
            $lineTotal = $unitPrice * $quantity;

            return [
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
            ];
        })->all();
    }
}
