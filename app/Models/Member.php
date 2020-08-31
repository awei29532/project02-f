<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;

use function PHPSTORM_META\elementType;

/**
 * @property int $id
 * @property int $agent_id
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $gender
 * @property int $status
 * @property int $level_id
 * @property int $rebate_id
 * @property string $cell_phone
 * @property string $id_number
 * @property string $country
 * @property string $email
 * @property string $address
 * @property string $line
 * @property string $remark
 * @property string $register_ip
 * @property string $birthday
 * @property string $invitation_code
 * @property string $updated_at
 * @property string $created_at
 */
class Member extends Auth implements JWTSubject
{
    use SoftDeletes;

    protected $table = 'member';

    protected $casts = [
        'id' => 'integer',
        'agent_id' => 'integer',
        'username' => 'string',
        'password' => 'string',
        'name' => 'string',
        'gender' => 'string',
        'status' => 'integer',
        'level_id' => 'integer',
        'rebate_id' => 'integer',
        'cell_phone' => 'string',
        'id_number' => 'string',
        'country' => 'string',
        'email' => 'string',
        'address' => 'string',
        'line' => 'string',
        'remark' => 'string',
        'register_ip' => 'string',
        'birthday' => 'string',
        'invitation_code' => 'string',
        'updated_at' => 'string',
        'created_at' => 'string',
    ];

    protected $fillable = [
        'username'
    ];

    protected $hidden = [
        'password',
    ];

    public function wallet()
    {
        return $this->hasOne(MemberWallet::class, 'member_id');
    }

    public function level()
    {
        return $this->hasOne(Level::class, 'id', 'level_id');
    }

    public function rebate()
    {
        return $this->hasOne(Rebate::class, 'id', 'rebate_id');
    }

    public function loginLog()
    {
        return $this->hasMany(MemberLoginLog::class, 'member_id');
    }

    public function getUpdatedAtAttribute()
    {
        return $this->attributes['updated_at'];
    }

    public function getCreatedAtAttribute()
    {
        return $this->attributes['created_at'];
    }

    public function basicInformationComplete()
    {
        return ($this->name
            && $this->line
            && $this->gender
            && $this->birthday) ? 1 : 0;
    }

    public function memberBank()
    {
        return $this->hasMany(MemberBank::class, 'member_id');
    }

    public function securityLevel()
    {
        $information = $this->basicInformationComplete();
        $memberBank = $this->memberBank();

        if (!$information && !$memberBank) {
            return 1;
        } elseif (!$information || !$memberBank) {
            return 2;
        } else {
            return 3;
        }
    }

    public function getCellPhoneAttribute()
    {
        $cell_phone = $this->attributes['cell_phone'];
        return $cell_phone ? (substr($cell_phone, 0, 3) . '***' . substr($cell_phone, -4)) : '';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
