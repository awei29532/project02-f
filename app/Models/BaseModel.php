<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function boot()
    {
        parent::boot();
    }

    public function getUpdatedAtAttribute()
    {
        return $this->attributes['updated_at'];
    }

    public function getCreatedAtAttribute()
    {
        return $this->attributes['created_at'];
    }
}
