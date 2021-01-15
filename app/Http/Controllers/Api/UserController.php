<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Models\User;
use App\Http\Validate\UsersValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userValidate;
    public function __construct(UsersValidate $validate)
    {
        $this->userValidate=$validate;
    }

    public function signup(Request $request)
    {
        $rules=['user_account' => 'unique:info_users','password' => 'confirmed'];
        $verifyRes=$this->userValidate->check($request->all(),'signup',$rules);
        if (!$verifyRes){
            return api(422,$this->userValidate->getError());
        }

        $user=new User([
            'user_account'=>$request->user_account,
            'nickname'=>$request->nickname,
            'password'=>bcrypt($request->password),
            'user_authority'=>$request->user_authority
        ]);

        $user->save();

        return api(200,'添加成功');
    }

    public function login(Request $request){
        $formData=request(['user_account','password']);
        $verifyRes=$this->userValidate->check($formData,'login');
        if (!$verifyRes){
            return api(422,$this->userValidate->getError());
        }

        if (!$jwt_token=Auth::guard('api')->attempt($formData)){
            return api(401,'账号或密码错误');
        }

        $user=Auth::user();

        return api(201,'success',[
            'access_token'=>$jwt_token,
            'token_type'=>'Bearer',
            'user'=>$user
        ]);
    }

    public function info(){
        $user = Auth::guard('api')->user();
        return api(200,'success',[
            'user'=>$user
        ]);
    }

    public function logout(){
        Auth::guard('api')->logout();
        return api(200,'已退出');
    }
}
