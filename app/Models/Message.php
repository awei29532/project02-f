<?php

namespace App\Models;

/**
 * @property int $id
 * @property array $member_ids
 * @property string $title
 * @property string $content
 * @property int $status
 * @property string $send_at
 * @property string $updated_at
 * @property string $created_at
 */
class Message extends BaseModel
{
    protected $table = 'message';

    protected $casts = [
        'id' => 'integer',
        'member_ids' => 'string',
        'title' => 'string',
        'content' => 'string',
        'status' => 'integer',
        'send_at' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];

    public function getSendCountAttribute()
    {
        return count($this->member_ids);
    }
}
