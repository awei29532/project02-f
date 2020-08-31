<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $lang
 * @property string $type
 * @property int $status
 * @property string $content
 * @property string $updated_at
 * @property string $created_at
 */
class Marquee extends BaseModel
{
    protected $table = 'marquee';

    protected $casts = [
        'id' => 'integer',
        'lang' => 'string',
        'type' => 'string',
        'status' => 'integer',
        'content' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];
}
