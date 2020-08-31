<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $member_id
 * @property string $device
 * @property string $browser
 * @property string $ip
 * @property string $updated_at
 * @property string $created_at
 */
class MemberLoginLog extends BaseModel
{
    protected $table = 'member_login_log';

    protected $casts = [
        'id' => 'integer',
        'member_id' => 'integer',
        'device' => 'string',
        'browser' => 'string',
        'ip' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];
}
