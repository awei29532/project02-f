<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $site_id
 * @property string $order_no
 * @property int $member_id
 * @property int $agent_id
 * @property int $company_id
 * @property string $game_type
 * @property string $game_round
 * @property string $game_play
 * @property string $game_currency
 * @property string $game_table
 * @property double $before_amount
 * @property double $amount
 * @property double $valid_amount
 * @property double $win
 * @property string $game_result
 * @property string $order_at
 * @property string $draw_at
 * @property string $ip
 * @property string $device
 * @property int $is_valid
 * @property string $created_at
 * @property string $updated_at
 */
class GameOrderRecord extends BaseModel
{
    protected $table = 'game_order_record';

    protected $casts = [
        'id' => 'integer',
        'site_id' => 'integer',
        'order_no' => 'string',
        'member_id' => 'integer',
        'agent_id' => 'integer',
        'company_id' => 'integer',
        'game_id' => 'integer',
        'game_type' => 'string',
        'game_round' => 'string',
        'game_play' => 'string',
        'game_currency' => 'string',
        'game_table' => 'string',
        'before_amount' => 'double',
        'amount' => 'double',
        'valid_amount' => 'double',
        'win' => 'double',
        'game_result' => 'string',
        'order_at' => 'string',
        'draw_at' => 'string',
        'ip' => 'string',
        'device' => 'string',
        'is_valid' => 'integer',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public function company()
    {
        return $this->hasOne(GameCompany::class, 'id', 'company_id');
    }

    public function game()
    {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }
}
