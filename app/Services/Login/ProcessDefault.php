<?php namespace App\Services\Login;

use App\Models\User as UserModel;
use App\Services\SC;
use App\Services\Login\AbstractProcess;
use Validator;

/**
 * 登录处理
 *
 * @author jiang <mylampblog@163.com>
 */
class ProcessDefault extends AbstractProcess {

    /**
     * 用户模型
     * 
     * @var object
     */
    private $userModel;

    /**
     * 初始化
     *
     * @access public
     */
    public function __construct()
    {
        if( ! $this->userModel) $this->userModel = new UserModel();
    }

    /**
     * 登录验证
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @access public
     * @return boolean false|用户的信息
     */
    public function check($username, $password)
    {
        $userInfo = $this->userModel->InfoByName($username);
        $sign = md5($userInfo['password'].$this->getPublicKey());
        $this->delPublicKey();
        if($sign == strtolower($password))
        {
            SC::setLoginSession($userInfo);
            return $userInfo;
        }
        return false;
    }

    /**
     * 检测post过来的数据
     * 
     * @param string $username 用户名
     * @param string $password 密码
     * @access public
     * @return false|string
     */
    public function validate($username, $password)
    {
        $data = array( 'username' => $username, 'password' => $password );
        $rules = array( 'username' => 'required|min:1', 'password' => 'required|min:1' );
        $messages = array( 'username.required' => '请输入用户名', 'username.min' => '请输入用户名',
            'password.required' => '请输入密码', 'password.min' => '请输入密码' );
        $validator = Validator::make($data, $rules, $messages);
        if ($validator->fails())
        {
            return $validator->messages()->first();
        }
        return false;
    }

    /**
     * 设置并返回加密密钥
     *
     * @return string 密钥
     */
    public function setPublicKey()
    {
        return SC::setPublicKey();
    }

    /**
     * 取得刚才设置的加密密钥
     * 
     * @return string 密钥
     */
    public function getPublicKey()
    {
        return SC::getPublicKey();
    }

    /**
     * 删除密钥
     * 
     * @return void
     */
    public function delPublicKey()
    {
        return SC::delPublicKey();
    }

    /**
     * 判断是否已经登录
     *
     * @return boolean true|false
     */
    public function hasLogin()
    {
        return SC::getLoginSession();
    }

    /**
     * 登录退出
     *
     * @return void
     */
    public function logout()
    {
        return SC::delLoginSession();
    }

}