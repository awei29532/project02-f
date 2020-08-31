<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $tran_type
 * @property string $tran_no
 * @property int $from_mid
 * @property string $from_account
 * @property double $from_balance
 * @property int $to_mid
 * @property string $to_account
 * @property double $to_balance
 * @property double $amount
 * @property string $created_at
 * @property int $operator_type
 * @property int $operator
 * @property string $remark
 */
class MemberTransactionRecord extends BaseModel
{
    protected $table = 'member_transaction_record';

    protected $casts = [
        'id' => 'integer',
        'tran_type' => 'integer',
        'tran_no' => 'string',
        'from_mid' => 'integer',
        'from_account' => 'string',
        'from_balance' => 'double',
        'to_mid' => 'integer',
        'to_account' => 'string',
        'to_balance' => 'double',
        'amount' => 'double',
        'created_at' => 'string',
        'operator_type' => 'integer',
        'operator' => 'integer',
        'remark' => 'string',
    ];
}
