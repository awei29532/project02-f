<?php

namespace App\Models;

/**
 * @property int $sn
 * @property int $member_id
 * @property int $bank_card_id
 * @property string $order_no
 * @property string $remark
 * @property int $type
 * @property int $cash_id
 * @property double $amount
 * @property string $username
 * @property int $deposit_type
 * @property string $member_bank
 * @property int $status
 * @property string $deposit_at
 * @property string $updated_at
 * @property string $created_at
 */
class MemberDeposit extends BaseModel
{
    protected $table = 'member_deposit';

    protected $primaryKey = 'sn';
}
