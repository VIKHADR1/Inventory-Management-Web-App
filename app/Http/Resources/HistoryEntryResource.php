<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HistoryEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $order = $this->relationLoaded('order') ? $this->order : null;
        $products = null;
        $productNames = null;

        if ($order && $order->relationLoaded('items') && $order->items->isNotEmpty()) {
            $products = $order->items
                ->map(fn ($item) => [
                    'id' => $item->product_id,
                    'name' => $item->product?->name,
                    'quantity' => $item->quantity,
                ])
                ->values();

            $productNames = $products
                ->pluck('name')
                ->filter()
                ->implode(', ');
        }

        return [
            'id' => $this->id,
            'service_date' => $this->service_date,
            'address' => $this->address,
            'customer_name' => $this->customer_name,
            'order_number' => $this->order_number,
            'product' => $productNames,
            'products' => $products,
            'status' => $order?->status,
            'amount' => $order?->total_amount,
            'order' => new OrderResource($this->whenLoaded('order')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'service_team' => new ServiceTeamResource($this->whenLoaded('serviceTeam')),
            'handled_by' => new EmployeeResource($this->whenLoaded('handledBy')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
