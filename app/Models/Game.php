<?php

namespace App\Models;

/**
 * @property int $id
 * @property int $company_id
 * @property string $game_code
 * @property string $type
 * @property int $status
 * @property string $updated_at
 * @property string $created_at
 */
class Game extends BaseModel
{
    const type_yabo = 'yabo';
    const type_live = 'live';
    const type_sport = 'sport';
    const type_lottery = 'lottery';
    const type_chess = 'chess';
    const type_electron = 'electron';

    protected $table = 'game';

    protected $casts = [
        'id' => 'integer',
        'company_id' => 'integer',
        'game_code' => 'string',
        'type' => 'string',
        'status' => 'integer',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];

    public function company() 
    {
        return $this->hasOne(GameCompany::class, 'id', 'company_id');
    }

    public function wallet()
    {
        return $this->hasMany(MemberGameWallet::class, 'member_id');
    }
}
