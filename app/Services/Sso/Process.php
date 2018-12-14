<?php namespace App\Services\Sso;

use Request, Config;
use App\Services\SC;
use App\Services\Sso\Ticket;
use App\Services\Login\Process as LoginProcess;

/**
 * 登录处理
 *
 * @author jiang <mylampblog@163.com>
 * @todo  多系统，多套用户，且字段都不一致的情况下。
 */
class Process {

    /**
     * service 参数
     *
     * @var string
     */
    private $service;

    /**
     * renew 参数，当存在这个参数的时候会退出登录状态
     *
     * @var void
     */
    private $renew;

    /**
     * 标志是否需要跳转回子应用
     * 
     * @var boolean
     */
    private $isJump;

    /**
     * 跳回子应用的连接
     * 
     * @var string
     */
    private $jumpUrl;

    /**
     * 是否已经登录
     * 
     * @var boolean
     */
    private $isLogin;

    /**
     * 错误的提示信息
     * 
     * @var string
     */
    private $errorMsg;

    /**
     * 配置文件
     *
     * @var array
     */
    private $config;

    /**
     * 错误信息：没有注册过的客户端
     *
     * @var const
     */
    CONST ERROR_SERVICE = 2001;

    /**
     * 初始化
     *
     * @access public
     */
    public function __construct()
    {
        $this->config = Config::get('sso.services');
    }

    /**
     * 单点登录的处理入口
     *
     * @access public
     * @return void
     */
    public function process($isHasLogin = false)
    {
        if($isHasLogin) $this->sendTicketGrantingTicket();

        $loginProcess = new LoginProcess();
        if($this->getRenew()) return $this->reSingin($loginProcess);

        if($this->ticketGrantingTicketExistsCheck($loginProcess)) return $this->hasBeenLogin();
    }

    /**
     * 删除TGC，并重新登录
     *
     * @access private
     * @return void
     */
    private function reSingin($loginProcess)
    {
        SC::delTgcCookie();
        $loginProcess->getProcess()->logout();
    }

    /**
     * 如果已经登录
     *
     * @access private
     * @return void
     */
    private function hasBeenLogin()
    {
        if( ! $this->serviceCheck()) return ;
        if($ticket = $this->granerateServiceTicket())
        {
            $this->isJump = true;
            $this->jumpUrl = $this->getService() . '?ticket=' . $ticket;
        }
    }

    /**
     * 检测有没有TGC
     *
     * @access private
     * @return boolean 有｜没有
     */
    private function ticketGrantingTicketExistsCheck($loginProcess)
    {
        if( ! SC::getTgcCookie() or ! $loginProcess->getProcess()->hasLogin()) return false;
        $this->isLogin = true;
        return true;
    }

    /**
     * 设置登录凭证
     *
     * @access private
     * @return void
     */
    private function sendTicketGrantingTicket()
    {
        SC::setTgcCookie( (new Ticket())->createTGC() );
    }

    /**
     * 检测是否带有service 参数
     *
     * @access private
     * @return boolean true|false
     */
    private function serviceCheck()
    {
        if( ! in_array($this->getService(), array_values($this->config)))
        {
            $this->errorMsg = self::ERROR_SERVICE;
            return false;
        }
        return true;
    }

    /**
     * 根据service参数及用户的相关信息生成ticket
     *
     * @access private
     * @return boolean true|false
     */
    private function granerateServiceTicket()
    {
        $tgc = SC::getTgcCookie();
        $userInfo = SC::getLoginSession();
        return (new Ticket())->createST($tgc, $this->getService(), $userInfo);
    }

    /**
     * 是否需要跳转
     *
     * @access public
     * @return boolean true|false
     */
    public function isJump()
    {
        return $this->isJump;
    }

    /**
     * 需要跳转的连接
     *
     * @access public
     * @return string
     */
    public function getJumpUrl()
    {
        return $this->jumpUrl;
    }

    /**
     * 设置service参数
     *
     * @access public
     * @param string $service 客户端地址
     * @return object
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * 返回刚才设置的客户端地址
     *
     * @access private
     * @return string
     */
    private function getService()
    {
        if(isset($this->config[$this->service])) return $this->config[$this->service];
        return false;
    }

    /**
     * 设置是否重置登录状态
     * 
     * @param void $renew 是否重置登录状态
     * @access public
     * @return object
     */
    public function setRenew($renew)
    {
        $this->renew = $renew;
        return $this;
    }

    /**
     * 返回刚才设置的是否重置登录状态
     *
     * @access private
     * @return boolean
     */
    private function getRenew()
    {
        return $this->renew;
    }

    /**
     * 是否已经登陆
     *
     * @access public
     * @return boolean true|false
     */
    public function getHasTGT()
    {
        return $this->isLogin;
    }

    /**
     * 返回错误的信息
     *
     * @access public
     * @return string 错误的信息
     */
    public function getErrormsg()
    {
        return $this->errorMsg;
    }

}