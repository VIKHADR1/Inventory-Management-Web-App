<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_number' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('orders', 'order_number')->ignore($this->order),
            ],
            'customer_id' => ['sometimes', 'exists:customers,id'],
            'status' => ['sometimes', 'in:processing,completed,cancelled'],
            'order_date' => ['sometimes', 'date'],
            'order_by' => ['nullable', 'string', 'max:255'],
            'service_required' => ['boolean'],
            'service_status' => ['sometimes', 'in:not_required,pending,assigned,on_field,completed'],
            'service_team_id' => ['nullable', 'exists:service_teams,id'],
            'handled_by_employee_id' => ['nullable', 'exists:employees,id'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'items' => ['nullable', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
