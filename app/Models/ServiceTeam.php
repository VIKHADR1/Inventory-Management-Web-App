<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'leader_employee_id',
    ];

    public function leader()
    {
        return $this->belongsTo(Employee::class, 'leader_employee_id');
    }

    public function members()
    {
        return $this->belongsToMany(Employee::class, 'service_team_members')
            ->withTimestamps();
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function historyEntries()
    {
        return $this->hasMany(HistoryEntry::class);
    }
}
