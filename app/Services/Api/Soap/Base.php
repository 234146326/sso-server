<?php namespace App\Services\Api\Soap;

use Log;
use App\Models\User as UserModel;

/**
 * 单点登录对外api
 *
 * @author jiang <mylampblog@163.com>
 */
class Base {

    /**
     * SOAP 参数不全
     */
    CONST ERROR_MISSING_AUTH_HEADER_PARPAM = '2001';

    /**
     * SOAP 用户验证失败
     */
    CONST ERROR_AUTHORISE = '2002';

    /**
     * 标识是否经验了验证
     * 
     * @var boolean
     */
    protected $authorized = false;

    /**
     * 身份验证，每次请求都会执行这个操作
     * 
     * @param object $param header中传过来的数据。
     * @access public
     */
    public function Authorise($headerParam)
    {
        //Log::info(serialize($headerParam->headerUser));
        if( ! isset($headerParam->headerUser, $headerParam->headerPassword, $headerParam->headerToken)) {
            throw new \SoapFault(self::ERROR_MISSING_AUTH_HEADER_PARPAM, 'Missing auth header param.');
        }
        $userInfo = (new UserModel())->InfoByName($headerParam->headerUser);
        if(empty($userInfo) or md5($headerParam->headerPassword) != $userInfo['password'] or $headerParam->headerToken != $userInfo['apitoken']) {
            throw new \SoapFault(self::ERROR_AUTHORISE, 'Acess deny.');
        }
        $this->authorized = true;
    }

    /**
     * 是否经验了验证
     * 
     * @return boolean
     */
    protected function checkAuthorise()
    {
        if($this->authorized !== true) {
            throw new \SoapFault(self::ERROR_AUTHORISE, 'Acess deny.');
        }
        return true;
    }

}