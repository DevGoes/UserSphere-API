<?php

namespace App\Models;

use App\Models\Helper\CodeUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use CodeUUID;

    protected $table = 'tasks';

    protected $fillable = [
        'id', 'project_id', 'name', 'description', 'status'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
