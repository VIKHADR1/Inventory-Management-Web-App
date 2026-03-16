<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'customer_id',
        'service_team_id',
        'handled_by_employee_id',
        'service_date',
        'address',
        'customer_name',
        'order_number',
        'notes',
    ];

    protected $casts = [
        'service_date' => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceTeam()
    {
        return $this->belongsTo(ServiceTeam::class);
    }

    public function handledBy()
    {
        return $this->belongsTo(Employee::class, 'handled_by_employee_id');
    }
}
