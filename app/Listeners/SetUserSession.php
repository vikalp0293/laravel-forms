<?php

namespace app\Listeners;

use Illuminate\Auth\Events\Login;
use Modules\User\Entities\Role;

class SetUserSession
{

    public function handle(Login $event)
    {

        $roleName = '';
        $role     = $event->user->getRoleNames()->toArray();
        if (!empty($role)) {
            $roleName = $role[0];
        }

        $roleDetail = Role::select('id')->where('name', $roleName)->first();

        if ($roleDetail) {
            $role_id = $roleDetail->id;
        }

        $roleDetail = Role::select('id')->where('name', $roleName)->first();

        if ($roleDetail) {
            $role_id = $roleDetail->id;
        }

        $token = $event->user->createToken('MyApp')->accessToken;
        // // $token = "asdasdklasdkjasdkjasdkjasd";

        // echo "string";
        // die;

        session(
            [
                'name'           => $event->user->name,
                'email'          => $event->user->email,
                'role'           => $roleName,
                // 'userPermission' => $userPermission,
                'token'          => $token,
            ]
        );
    }
}
