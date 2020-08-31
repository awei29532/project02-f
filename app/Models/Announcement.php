<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $content
 * @property int $status
 * @property string $lang
 * @property string $updated_at
 * @property string $created_at
 */
class Announcement extends BaseModel
{
    protected $table = 'announcement';

    protected $casts = [
        'id' => 'integer',
        'content' => 'string',
        'status' => 'integer',
        'lang' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];
}
