<?php

namespace App\Models\Helper;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait CodeUUID
{
    protected static function booted()
    {
        static::creating(function(Model $model){
            if (!$model->id){
                $model->id = (string)Uuid::uuid4();
            }
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }
}
