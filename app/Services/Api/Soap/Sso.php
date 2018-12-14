<?php namespace App\Services\Api\Soap;

use App\Services\Api\Soap\Base;
use App\Models\ServiceTicket as ServiceTicketModel;
use App\Models\User as UserModel;
use App\Services\Sso\Ticket;
use Log;

/**
 * 单点登录对外api
 *
 * @author jiang <mylampblog@163.com>
 */
class Sso extends Base {

    /**
     * 验证客户端的ST票据、每次验证都会删除票据缓存。
     *
     * @param string $serviceTicket
     * @return array 用户的信息
     */
    public function checkServiceTicket($serviceTicket)
    {
        $this->checkAuthorise();
        $serviceTicketModel = new ServiceTicketModel();
        $checkServiceTicket = $serviceTicketModel->infoByServiceTicket($serviceTicket);
        $serviceTicketModel->deleteServiceTicketCache($serviceTicket);
        //如果没有缓存票据，或者票据的时候过期了，那么返回false
        if( ! $checkServiceTicket or ! isset($checkServiceTicket['time']) or (time() - $checkServiceTicket['time'] > 10) ) {
            return false;
        }
        $tickeInfo = str_replace(Ticket::ST_PREFIX, '', $serviceTicket);
        $decode = \Crypt::decrypt($tickeInfo);
        $ticketSpider = explode(Ticket::ST_SPIDER, $decode);
        if( ! isset($ticketSpider[2])) return false;
        $userInfo = (new UserModel())->InfoByName($ticketSpider[2]);
        unset($userInfo['password']);
        return $userInfo;
    }

}