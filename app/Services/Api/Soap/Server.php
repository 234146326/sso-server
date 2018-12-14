<?php namespace App\Services\Api\Soap;

/**
 * 登录处理对外服务
 *
 * @author jiang <mylampblog@163.com>
 */
class Server {

    /**
     * 启动服务
     */
    public function buildSoapServer()
    {
        $server = new \SoapServer(null, array('uri' => 'abc'));
        $server->setObject(new \App\Services\Api\Soap\Sso());
        ob_start();
        $server->handle();
        $soapXml = ob_get_contents();
        ob_end_clean();
        return $soapXml;
    }
}