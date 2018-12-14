<?php namespace App\Services\Login;

use Config;
use App\Services\Login\AbstractProcess;

/**
 * 登录处理
 *
 * @author jiang <mylampblog@163.com>
 */
class Process {

    /**
     * 登录处理对象
     * 
     * @var object
     */
    private $process;

    /**
     * 初始化
     *
     * @access public
     */
    public function __construct()
    {
        $useProcess = '\\App\\Services\\Login\\Process' . ucfirst(Config::get('sso.login_process'));
        $class = new $useProcess();
        $check = $class instanceof AbstractProcess;
        if( ! $check) throw new \Exception("login process class must be instanceof AbstractProcess!!");
        
        if( ! $this->process) $this->process = new $class;
    }

    /**
     * 返回登录处理的对象
     *
     * @return object
     */
    public function getProcess()
    {
        return $this->process;
    }

}