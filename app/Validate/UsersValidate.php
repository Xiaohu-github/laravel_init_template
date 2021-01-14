<?php


namespace App\Http\Validate;


class UsersValidate extends  Validate
{
       /**
        * 用户验证规则
        * @var array
        */
        protected $rule=[
            'user_account' => 'required|string|between:5,16|alpha',
            'nickname' => 'required|string',
            'password' => 'required|string|between:5,16',
            'password_confirmation' => 'required|string|between:5,16',
            'user_authority' => 'required|int'
        ];

        /**
         * 自定义验证失败的返回信息
         * @var array
         */
        protected $message=[
            'user_account.required'=>'请填写用户登录账号',
            'user_account.string'=>'账号应由字母构成',
            'user_account.between'=>'账号应为5~16个字符',
            'user_account.alpha'=>'账号应由字母构成',
            'user_account.unique'=>'账号已存在',

            'nickname.required'=>'请填写姓名',
            'nickname.string'=>'姓名为字符串格式',

            'password.required'=>'密码不能为空',
            'password.string'=>'密码格式不正确',
            'password.confirmed'=>'密码不一致',
            'password.between'=>'密码长度为5~16个字符',

            'password_confirmation.required'=>'确认密码不正确',
            'password_confirmation.string'=>'确认密码不正确',
            'password_confirmation.between'=>'确认密码不正确',

            'user_authority.required'=>'用户权限不能为空',
            'user_authority.int'=>'用户权限有错误'
        ];

        /**
         * 验证场景
         * @var array
         */
        protected $scene = [
            'signup' => "user_account,nickname,password,password_confirmation,user_authority",
            'edit'=>'nickname,password,password_confirmation,user_authority',
            'resetPwd'=>'password,password_confirmation',
            'login'=>'user_account,password'
        ];
}
