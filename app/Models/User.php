<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 用户表模型
 */
class User extends Model {

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
    protected $table = 'users';

    /**
     * 取得用户的信息，根据用户名
     * 数据结构为：return ['username' => 'test', 'password' => '96e79218965eb72c92a549dd5a330112', 'apitoken' => '111111'];
     * 
     * @param string $username 用户名
     */
    public function InfoByName($username)
    {
        return $this->where('username', $username)->first()->toArray();
    }

}
