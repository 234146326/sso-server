<?php namespace App\Services\Sso;

use App\Models\ServiceTicket as ServiceTicketModel;

/**
 * 票据生成
 *
 * @author jinag <mylampblog@163.com>
 */

class Ticket {

    /**
     * 数字
     *
     * @var const
     */
    const NUMS = "0123456789";

    /**
     * 字母
     *
     * @var const
     */
    const ZMS = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

    /**
     * TGC的前缀
     *
     * @var const
     */
    CONST TGC_PREFIX = 'TGC-';

    /**
     * ST的前缀
     *
     * @var const
     */
    CONST ST_PREFIX = 'ST-';

    /**
     * ST的分隔
     *
     * @var const
     */
    CONST ST_SPIDER = '---';

    /**
     * 生成TGC的值
     *
     * @access public
     * @return string
     */
    public function createTGC()
    {
        $string = $this->getRandomString(self::NUMS.self::ZMS, 50);
        return self::TGC_PREFIX.$string;
    }

    /**
     * 生成ST
     *
     * @param string $tgc 登录成功时所保存的cookie
     * @param string $service 客户端域名
     * @param array $userInfo 用户的登录信息
     * @access public
     * @return false|string
     */
    public function createST($tgc, $service, $userInfo)
    {
        $randomString = uniqid(true);
        $value = $tgc . self::ST_SPIDER . $service . self::ST_SPIDER . $userInfo['username'];
        $ticket = self::ST_PREFIX . \Crypt::encrypt($value);
        $save = ( new ServiceTicketModel() )->saveServiceTicket($ticket);
        if($save) return $ticket;
        return false;
    }

    /**
     * 生成随机的字符串
     *
     * @param string $string 字符池
     * @param int $length 生成的长度
     * @access public
     * @return string
     */
    private function getRandomString($string, $length)
    {
        $randomString = "";
        for ($i = 0; $i < $length; $i++)
            $randomString .= $string[(mt_rand(0, (strlen($string) - 1)))];
        return $randomString;
    }

}