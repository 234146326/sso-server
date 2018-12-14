<?php namespace App\Http\Controllers;

use App\Services\Api\Soap\Server as SoapServer;

/**
 * Api中心
 *
 * @author jiang <mylampblog@163.com>
 */
class ApiController extends Controller {

    /**
     * soap的实现。
     *
     * @access public
     */
    public function postV1(SoapServer $server)
    {
        return $server->buildSoapServer();
    }

}