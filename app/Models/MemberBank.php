<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $sn
 * @property int $member_id
 * @property int $type
 * @property int $bank_id
 * @property string $branch_name
 * @property string $account
 * @property string $name
 * @property string $updated_at
 * @property string $created_at
 */
class MemberBank extends BaseModel
{
    protected $table = 'member_bank';

    protected $casts = [
        'id' => 'integer',
        'sn' => 'string',
        'member_id' => 'integer',
        'type' => 'integer',
        'bank_id' => 'integer',
        'branch_name' => 'string',
        'account' => 'string',
        'name' => 'string',
        'udpated_at' => 'string',
        'created_at' => 'string',
    ];
}
