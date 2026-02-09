<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHistoryEntryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => ['required', 'exists:orders,id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'service_team_id' => ['nullable', 'exists:service_teams,id'],
            'handled_by_employee_id' => ['nullable', 'exists:employees,id'],
            'service_date' => ['required', 'date'],
            'address' => ['required', 'string'],
            'customer_name' => ['required', 'string', 'max:255'],
            'order_number' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
