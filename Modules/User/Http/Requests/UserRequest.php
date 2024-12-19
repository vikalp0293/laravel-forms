<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiBaseController;
use Config;
use App\Http\Requests\BaseRequest;
use Corals\User\Models\User;
use Modules\User\Entities\Role;

class UserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = array();

        $rules = [
            'firstname' => 'required|max:75',
            'lastname' => 'required|max:75',
            'role' => 'required',
        ];
        
        if($this->request->get('userFound') == 0){
            $rules = array_merge($rules, [
                'file' => 'mimes:jpg,jpeg,png|max:3000'
            ]);
        }

        if($this->isStore() && $this->request->get('userFound') == 0) {
            $rules = array_merge($rules, [
                'mobileNumber' => 'required|max:10|unique:users,phone_number',
                'email' => 'required|email|max:191|unique:users,email',
            ]);   
        }

        if($this->request->get('userId')) {
            $userId = $this->request->get('userId');
            $rules = array_merge($rules, [
                'mobileNumber' => 'required|max:10|unique:users,phone_number,' . $userId,
                'email' => 'required|email|max:191|unique:users,email,' . $userId,
            ]);   
        }

        if ($this->isStore() && $this->request->get('userFound') == 0 && !$this->request->get('userId')) {
            $rules = array_merge($rules, [
                    'password' => 'required|confirmed|max:191|min:8',
                    'password_confirmation' => 'required'
                ]
            );
        }


        $roleData = Role::findorfail($this->request->get('role'));

        if($roleData->name == \Config::get('constants.ROLES.BUYER')){

            $rules = array_merge($rules, [
                    'shopname' => 'required',
                    'category' => 'required',
                    // 'creditLimit' => 'sometimes|numeric|min:0|max:10000000'
                ]
            );


            if($this->isStore() && $this->request->get('userFound') == 0) {

                if($this->request->get('gst') != ""){
                    $rules = array_merge($rules, [
                            'gst' => 'min:15|unique:users,gst|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
                        ]
                    );
                }

            }

            if($this->request->get('userId')) {
                $userId = $this->request->get('userId');
                if($this->request->get('gst') != ""){
                    $rules = array_merge($rules, [
                            'gst' => 'min:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/|unique:users,gst,' . $userId,
                        ]
                    );
                }
            }

        }

        $rules = array_merge($rules, [
                'address1' => 'required',
                'country' => 'required',
                'state' => 'required',
                'district' => 'required',
                'city' => 'required',
                'pincode' => 'required|numeric',
            ]
        );
        return $rules;
    }
}
