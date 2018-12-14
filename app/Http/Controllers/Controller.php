<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * 父控制类类
 *
 * @author jiang <mylampblog@163.com>
 */
abstract class Controller extends BaseController
{
    use DispatchesCommands, ValidatesRequests;

    function __construct()
    {
//        type(new \Illuminate\Http\Request());
    }
}
