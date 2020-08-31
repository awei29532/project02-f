<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $name
 * @property string $remark
 * @property string $updated_at
 * @property string $created_at
 */
class Level extends BaseModel
{
    protected $table = 'level';

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'remark' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];
}
