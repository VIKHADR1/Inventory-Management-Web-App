<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'job_title',
        'address',
        'hired_at',
        'is_active',
        'service_team_id',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'is_active' => 'boolean',
    ];

    public function serviceTeam()
    {
        return $this->belongsTo(ServiceTeam::class);
    }

    public function ledServiceTeams()
    {
        return $this->hasMany(ServiceTeam::class, 'leader_employee_id');
    }

    public function handledOrders()
    {
        return $this->hasMany(Order::class, 'handled_by_employee_id');
    }

    public function historyEntries()
    {
        return $this->hasMany(HistoryEntry::class, 'handled_by_employee_id');
    }
}
