<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $name
 * @property int $preset
 * @property string $type
 * @property string $updated_at
 * @property string $created_at
 */
class Rebate extends BaseModel
{
    protected $table = 'rebate';

    protected $casts = [
        'id' => 'integer',
    ];

    public function config()
    {
        return $this->hasMany(RebateConfig::class, 'rebate_id');
    }
}
