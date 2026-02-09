<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $latestServiceStatus = null;
        $totalOrders = null;
        $lastOrder = null;

        if ($this->relationLoaded('orders') && $this->orders->isNotEmpty()) {
            $latestOrder = $this->orders
                ->sortByDesc('order_date')
                ->first();

            $latestServiceStatus = $latestOrder->service_status;
            $totalOrders = $this->orders->count();
            $lastOrder = [
                'id' => $latestOrder->id,
                'order_number' => $latestOrder->order_number,
                'order_date' => $latestOrder->order_date,
                'status' => $latestOrder->status,
                'total_amount' => $latestOrder->total_amount,
                'service_status' => $latestOrder->service_status,
            ];
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'latest_service_status' => $latestServiceStatus,
            'total_orders' => $totalOrders,
            'last_order' => $lastOrder,
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
