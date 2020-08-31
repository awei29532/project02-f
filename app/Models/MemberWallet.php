<?php

namespace App\Models;

/**
 * @property int $member_id
 * @property double $amount
 * @property string $updated_at
 * @property string $created_at
 */
class MemberWallet extends BaseModel
{
    protected $table = 'member_wallet';

    protected $primaryKey = 'member_id';
}
