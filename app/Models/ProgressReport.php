<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressReport extends Model
{
    protected $fillable = [
        'project_id',
        'reported_by',
        'week_number',
        'description',
        'percentage',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
