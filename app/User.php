<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    protected $connection = "mysql";
    protected $table = "users";
    protected $primaryKey = "id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'branchID','MSISDN','firstName','middleName','lastName','fullName','name', 'email', 'physical_address', 'password','idTypeID','idNumber','userTypeID','ci_code', 'c_product', 'kra_pin', 'role','username','password','loginAttemps',
        'accountLocked','location','latitude','longitude','loggedInAt','online','durationOnline','signature',
        'emailVerifiedAt','loggedOutAt','minAmount','maxAmount','active','status','dateModified','dateCreated'
    ];
    public $timestamps= false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function assessment()
    {
        return $this->hasMany(Assessment::class, 'userID', 'id');
    }
}
