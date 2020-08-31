<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $name
 * @property string $domain
 * @property string $status
 * @property string $updated_at
 * @property string $created_at
 */
class Site extends BaseModel
{
    protected $table = 'site';

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'domain' => 'string',
        'status' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];
}
