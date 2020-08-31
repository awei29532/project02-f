<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $site_id
 * @property int $game_id
 * @property int $company_id
 * @property string $game_type
 * @property int $status
 * @property string $maintain_type
 * @property string $start_at
 * @property string $end_at
 * @property string $remark
 * @property string $updated_at
 * @property string $created_at
 */
class GameMaintenance extends BaseModel
{
    protected $table = 'game_maintenance';

    protected $casts = [
        'site_id' => 'integer',
        'game_id' => 'integer',
        'company_id' => 'integer',
        'game_type' => 'string',
        'status' => 'integer',
        'maintain_type' => 'string',
        'start_at' => 'string',
        'end_at' => 'string',
        'remark' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];

    protected $primaryKey = [
        'site_id',
        'game_id',
    ];

    public $incrementing = false;

    public function game()
    {
        return $this->hasOne(Game::class, 'id', 'game_id');
    }

    public function company()
    {
        return $this->hasOne(GameCompany::class, 'id', 'company_id');
    }
    
    /**
    * Set the keys for a save update query.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */
   protected function setKeysForSaveQuery(Builder $query)
   {
       $keys = $this->getKeyName();
       if(!is_array($keys)){
           return parent::setKeysForSaveQuery($query);
       }
   
       foreach($keys as $keyName){
           $query->where($keyName, '=', $this->getKeyForSaveQuery($keyName));
       }
   
       return $query;
   }
   
   /**
    * Get the primary key value for a save query.
    *
    * @param mixed $keyName
    * @return mixed
    */
   protected function getKeyForSaveQuery($keyName = null)
   {
       if(is_null($keyName)){
           $keyName = $this->getKeyName();
       }
   
       if (isset($this->original[$keyName])) {
           return $this->original[$keyName];
       }
   
       return $this->getAttribute($keyName);
   }
}
