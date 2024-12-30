<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use DB;
use App\Notifications\EmailVerificationMail;

use Modules\Hotels\Entities\BookingDetail;
use Modules\Hotels\Entities\Invoice;

class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use HasApiTokens, Notifiable, HasRoles;

    protected $table = 'users';
    protected $guard_name = 'web';



    /*public function role()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }*/

    public static function getUserPermissionsViaRoles($id){
        $user = User::find($id);
        $permissions = $user->getPermissionsViaRoles();
        $permissionList = [];
        if($permissions && count($permissions) > 0) {
            foreach ($permissions as $permission){
                array_push($permissionList, trim($permission->name));
            }
        }
        return $permissionList;
    }

    public static function getUserRoles($id){
        $roles = self::findOrfail($id)->getRoleNames();
        return $roles->toArray();
    }

    public function getFullNameAttribute()
    {
        return $this->name . ' ' . $this->last_name;
    }

    
}
