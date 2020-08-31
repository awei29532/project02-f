<?php

namespace App\Models;

/**
 * @property int $id
 * @property string $name
 * @property int $status
 * @property string $type
 * @property string $updated_at
 * @property string $created_at
 */
class GameCompany extends BaseModel
{
    protected $table = 'game_company';

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'status' => 'integer',
        'type' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];

    public function game()
    {
        return $this->hasMany(Game::class, 'company_id');
    }
}
