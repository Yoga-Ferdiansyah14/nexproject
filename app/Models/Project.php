<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'description',
        'start_date',
        'end_date',
        'status',
        'team_leader_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function teamLeader()
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    public function members()
    {
        return $this->hasMany(ProjectMember::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function progressReports()
    {
        return $this->hasMany(ProgressReport::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function getProgressAttribute(): int
    {
        $latestReport = $this->progressReports()->latest()->first();
        return $latestReport ? $latestReport->percentage : 0;
    }
}
