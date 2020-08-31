<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $member_id
 * @property int $company_id
 * @property double $amount
 * @property string $created_at
 * @property string $updated_at
 */
class MemberGameWallet extends BaseModel
{
    protected $table = 'member_game_wallet';

    protected $fillable = [
        'member_id',
        'company_id',
    ];

    protected $casts = [
        'member_id' => 'integer',
        'company_id' => 'integer',
        'amount' => 'double',
        'created_at' => 'string',
        'updated_at' => 'string',
    ];

    public $incrementing = false;

    protected $primaryKey = [
        'member_id',
        'company_id',
    ];

    public function member()
    {
        return $this->hasOne(Member::class, 'id', 'member_id');
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
