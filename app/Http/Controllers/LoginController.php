<?php namespace App\Http\Controllers;

use App\Services\Login\Process as LoginProcess;
use App\Services\SC;
use App\Services\Sso\Process as SsoProcess;
use Request;

/**
 * 登录相关
 *
 * @author jiang <mylampblog@163.com>
 */
class LoginController extends Controller {

    /**
     * 登录页面，如果没有登录会显示登录页面，如果已经登录但是没有service参数，那么会显示
     * 已经登录提示，如果带有service参数并且在已经注册的信息中，那么会跳转客户端。
     *
     * @param App\Services\Sso\Process $ssoProcess 单点登录核心处理
     * @access public
     */
    public function index(SsoProcess $ssoProcess)
    {
        $service = Request::input('service');
        $renew = Request::input('renew');
        $ssoProcess->setService($service)->setRenew($renew)->process();

        if($ssoProcess->isJump()) return redirect($ssoProcess->getJumpUrl());
        if($ssoProcess->getHasTGT()) return '已经登录';
        return view('login.index');
    }

    /**
     * @param SsoProcess $ssoProcess
     */
    public function logout(SsoProcess $ssoProcess)
    {
        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        SC::delLoginSession();
        header("Content-Type: application/json");
        echo json_encode(msg_array(1,'logout success !'));
//        die;
    }

    /**
     * 初始化登录，返回加密密钥
     *
     * @param App\Services\Login\Process $loginProcess 登录核心处理
     * @access public
     */
    public function getPrelogin(LoginProcess $loginProcess)
    {
        $publicKey = $loginProcess->getProcess()->setPublicKey();
        return response()->json(['pKey' => $publicKey])
                ->setCallback(Request::input('callback'));
    }

    /**
     * 开始登录，登录成功后会跳转到客户端
     *
     * @param App\Services\Login\Process $loginProcess 登录核心处理
     * @param App\Services\Sso\Process $ssoProcess 单点登录核心处理
     * @access public
     */
    public function getProc(LoginProcess $loginProcess, SsoProcess $ssoProcess)
    {
        $username = Request::input('username');
        $password = Request::input('password');
        $service = Request::input('service');
        $callback = Request::input('callback');
        if($error = $loginProcess->getProcess()->validate($username, $password))
            return response()->json(['msg' => $error, 'result' => false])->setCallback($callback);

        if($userInfo = $loginProcess->getProcess()->check($username, $password))
        {
            $ssoProcess->setService($service)->process(true);
            if($ssoProcess->isJump())
                $result = ['msg' => '登录成功', 'jumpUrl' => $ssoProcess->getJumpUrl(), 'result' => true];
            else
                $result = ['msg' => '登录成功', 'result' => true];
        }
        else
        {
            $result = ['msg' => '登录失败', 'result' => false];
        }
        
        return response()->json($result)->setCallback($callback);
    }

}