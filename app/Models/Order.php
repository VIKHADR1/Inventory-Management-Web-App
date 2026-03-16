<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'status',
        'order_date',
        'order_by',
        'service_required',
        'service_status',
        'service_team_id',
        'handled_by_employee_id',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'service_required' => 'boolean',
        'total_amount' => 'decimal:2',
    ];

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

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
