<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 票据表模型
 *
 * @author jiang <mylampblog@163.com>
 */
class ServiceTicket extends Model {

    /**
     * 关闭自动维护updated_at、created_at字段
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * 数据表名
     * 
     * @var string
     */
    protected $table = 'service_ticket';

    /**
     * 可以被集体赋值的字段
     * 
     * @var array
     */
    protected $fillable = array('id', 'ticket', 'time');

    /**
     * 缓存票据
     */
    public function saveServiceTicket($ticket)
    {
        $data = ['ticket' => $ticket, 'time' => time()];
        return $this->create($data);
    }

    /**
     * 根据ST，检测ST是否存在
     *
     * @param string $serviceTicket
     */
    public function infoByServiceTicket($serviceTicket)
    {
        return $this->where('ticket', $serviceTicket)->first();
    }

    /**
     * 验证的时候，都要删除缓存的票据
     *
     * @access public
     */
    public function deleteServiceTicketCache($serviceTicket)
    {
        return $this->where('ticket', $serviceTicket)->delete();
    }

}
