<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'order_date' => $this->order_date,
            'order_by' => $this->order_by,
            'service_required' => $this->service_required,
            'service_status' => $this->service_status,
            'total_amount' => $this->total_amount,
            'notes' => $this->notes,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'service_team' => new ServiceTeamResource($this->whenLoaded('serviceTeam')),
            'handled_by' => new EmployeeResource($this->whenLoaded('handledBy')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
