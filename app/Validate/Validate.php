<?php


namespace App\Http\Validate;

use Illuminate\Support\Facades\Validator;

class Validate
{
    /**
     * 当前验证规则
     * @var array
     */
    protected $rule = [];

    /**
     * 验证提示信息
     * @var array
     */
    protected $message = [];

    /**
     * 验证场景定义
     * @var array
     */
    protected $scene = [];

    /**
     * 设置当前验证场景
     * @var array
     */
    protected $currentScene = null;

    /**
     * 验证失败错误信息
     * @var array
     */
    protected  $error = [];

    /**
     * 场景需要验证的规则
     * @var array
     */
    protected $only = [];


    /**
     * 设置验证场景
     * @access public
     * @param string $name 场景名
     * @return $this
     */
    public function scene($name)
    {
        // 设置当前场景
        $this->currentScene = $name;

        return $this;
    }

    /**
     * 数据验证
     * @access public
     * @param array $data 数据
     * @param mixed $rules 在原验证规则上自定义验证规则
     * @param array $message 自定义验证信息
     * @param string $scene 验证场景
     * @return bool
     */
    public function check($data, $scene = '', $rules = [], $message = [])
    {
        $this->setRule($rules);

        if (empty($message)) {
            $message = $this->message;
        }

        //读取场景
        if (!$this->getScene($scene)) {
            return false;
        }

        //如果场景需要验证的规则不为空
        if (!empty($this->only)) {
            $new_rules = [];
            foreach ($this->only as $key => $value) {
                if (array_key_exists($value, $rules)) {
                    $new_rules[$value] = $rules[$value];
                }
            }
            $rules = $new_rules;
        }

        $validator = Validator::make($data, $rules, $message);
        //验证失败
        if ($validator->fails()) {
            $this->error = $validator->errors()->first();
            return false;
        }

        return !empty($this->error) ? false : true;
    }

    /**
     * 获取数据验证的场景
     * @access protected
     * @param string $scene 验证场景
     * @return void
     */
    protected function getScene($scene = '')
    {
        // 读取指定场景
        if (empty($scene)) $scene = $this->currentScene;

        $this->only = [];

        if (empty($scene)) return true;

        if (!isset($this->scene[$scene])) {
            //指定场景未找到写入error
            $this->error = "scene:" . $scene . 'is not found';
            return false;
        }

        // 如果设置了验证适用场景
        $scene = $this->scene[$scene];
        if (is_string($scene)) $scene = explode(',', $scene);

        //将场景需要验证的字段填充入only
        $this->only = $scene;
        return true;
    }

    // 获取错误信息
    public function getError()
    {
        return $this->error;
    }

    protected function setRule($rules)
    {
        if (!empty($rules)) {
            foreach ($rules as $k => $v) {
                if (isset($this->rule[$k])) {
                    $rulesValue = explode('|', $v);
                    $rule = explode('|', $this->rule[$k]);
                    $mergeRule = array_merge($rulesValue, $rule);
                    $this->rule[$k] = implode('|', $mergeRule);
                } else {
                    $this->rule[] = $k;
                }
            }
        }
    }
}
