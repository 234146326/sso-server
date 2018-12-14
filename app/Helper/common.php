<?php

/**
 * print debug
 */
function debug(){
    debug_print_backtrace();
}

/**
 * fixme 打印类详情
 * @param $class object 对象
 */
function type($class){
    $ref = new \ReflectionClass($class);
    $br = '<br>';
    $consts = $ref->getConstants(); //返回所有常量名和值
    echo "----------------consts:---------------" . $br;
    foreach ($consts  as $key => $val)
    {
        echo "$key : $val" . $br;
    }

    $props = $ref->getDefaultProperties();  //返回类中所有属性与值类型
    echo "--------------------props:--------------" . $br . $br;
    foreach ($props as $key => $val)
    {
        echo "$key ::" . gettype($val) . $br;   //  属性名和属性值
    }

    $methods = $ref->getMethods();     //返回类中所有方法与参数
    echo "-----------------methods:---------------" . $br . $br;
    foreach ($methods as $method) {
        $getParam = $method->getParameters();
        if(!count($getParam))echo $method->getName();
        foreach ($getParam as $key=>$value){
            echo $method->getName() ."::".$value.' + ';
        }
        echo $br;
    }
}

// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * @param $arr
 * @param $keyName
 * @return mixed|string
 */
function is_issetKey($arr,$keyName){
    if(is_array($arr) && isset($arr[$keyName]))
    {
        return $arr[$keyName];
    }
    return '';
}

/**
 * @param $arr
 * @return string
 * 接受到的数据转换成字符存储
 */
function arr_to_str($arr,$implode= '|')
{
    $newarr=[];
    if(!is_array($arr)){
        return 0;
    };
    foreach ($arr as $keies => $values){
        $newarr[] = $values;
    }
    return implode($implode,$newarr);
}

//================= url =======================
function _redirect($url_http,$parma=[])
{
    echo  "<script language='javascript'>window.top.location.href='$url_http'</script>";
    die;
}
//=================== url ===============
/**
 * @param $arr
 * @return mixed */
function unset_array_value($arr,$val){
    foreach ($arr as $key=>$value)
    {
        if ($value == $val)
            unset($arr[$key]);
    }
    return $arr;
}

/**
 * @param $data
 * @param $id
 * @param array $new_arr
 * @return array
 */
function whileWhere($data,$id,$idName,$parentName,&$new_arr=[],$mode='mode:node')
{
    $select_frist = get_child_array($data,$id,$idName,$parentName,$mode);
    foreach ($select_frist as $Keies => $value) {
        $new_obj = get_child_array($data,$value[$idName],$idName,$parentName,$mode);
        if(count($new_obj)){
            $new_value = [];
            if($mode === 'mode:node'){
                $new_arr[] = array_merge($value,["node"=>whileWhere($data,$value[$idName],$idName,$parentName,$new_value,$mode)]);
            }elseif($mode === 'mode:array_push'){
                $nodes = whileWhere($data,$value[$idName],$idName,$parentName,$new_value,$mode);
                array_push($new_arr,$value);
                if(count($nodes))
                    foreach ($nodes as $key =>$vlaue){
                        array_push($new_arr,$vlaue);
                    }
            }
            continue;
        }
        $new_arr[] = $value;
    };
    return $new_arr;
}

/**
 * @param $data
 * @param $id
 * @return array
 */
function get_child_array($data,$id,$idName,$parentName,$mode)
{
    $new_data = [];
    foreach ($data as $k=>$v){
        if($v[$parentName] === $id){
            $new_data[] = array_merge($data[$k],is_child($data,$v[$idName],$parentName));
        }
    }
    return $new_data;
}

/**
 * @param $data
 * @param $id
 * @return array
 */
function is_child($data,$id,$parentName)
{
    foreach ($data as $k=>$v){
        if($id === $v[$parentName] ){
            return ['child'=>true];
        }
    }
    return ['child'=>false];
}


/**
 * ==========================================================
 * |  TODO ARRAY HANDLE   START
 * ========================
 */

/**
 * 返回过滤的数据
 * @param array $data 返回数组
 * @param array $name 名称数组
 * @param string $type 类型
 * @return array
 */
function check_request_value($data = [],$name = [],$type = 'str')
{
    $req = $data;
    if(!count($name) || !count($req)){return $req;}
    if($type === 'id'){
        foreach($name as $k=>$v)
        {
            if(!isset($req[$v]) || !is_numeric($req[$v])){
                unset($req[$v]);
            }
        }

    }elseif ($type === 'str'){
        foreach($name as $k=>$v)
        {
            if(!isset($req[$v]) || empty($req[$v])){
                if($req[$v] !== '0'){
                    unset($req[$v]);
                }
            }
        }
    }
    return $req;
}

/**
 * 校验日期格式是否正确
 *
 * @param string $date 日期
 * @param string $formats 需要检验的格式数组
 * @return boolean
 */
function check_date_is_valid($date, $formats = array("Y-m-d", "Y/m/d")) {
    $unixTime = strtotime($date);
    if (!$unixTime) { //strtotime转换不对，日期格式显然不对。
        return false;
    }
    //校验日期的有效性，只要满足其中一个格式就OK
    foreach ($formats as $format) {
        if (date($format, $unixTime) == $date) {
            return true;
        }
    }

    return false;
}

/**
 * 获取字段的和
 * Query columns and array_sum
 * @param $arr
 * @param string $name
 * @param string $type
 * @return array|float|int
 */
function sum_select_column($arr=[],$name='',$type='money')
{
    if(!count($arr) || !array_key_exists($name,$arr[0])){
        return msg_array(0,'key no found!');
    }
    $value = array_sum(array_column($arr,$name));
    $new_value = (int) $value;
    if($type !== 'money'){
        return round($new_value);
    }
    return round($new_value,2);
}

//TODO ARRAY HANDLE END


/**
 * 批量添加选中字段
 * Batch returns whether or not to be hit
 * @param array $data
 * @param array $select_data
 * @param $id
 * @return array|bool
 */
function data_to_select($data=[],$select_data=[],$id)
{
    if(!is_array($data)&& !is_array($data)){
        return false;
    }
    $new_arr=[];
    foreach ($data as $key=>$value){
        $selected = 0;
        foreach ($select_data as $keies=>$val){
            if($val[$id] == $value[$id]){
                $selected = 1;
            }
        }
        $new_arr[] = array_merge(['selected'=> $selected],$value);
    }
    return $new_arr;
}

/**
 * 获取$data指定$option的数据
 * @param $data array
 * @param array $option array
 * @return array|bool
 */
function get_data_field($data,$option=[])
{
    if(!is_array($data) && !is_array($option)){
        return false;
    }
    $new_arr=[];
    foreach ($data as $keies=>$value)
    {
        $arr=[];
        foreach ($option as $k){
            $arr[$k] =  $value[$k];
        }
        $new_arr[] = $arr;
    }
    return $new_arr;
}

/**
 *
 * 过滤需要的字段
 * @param $data
 * @param array $option
 * @return array|bool
 */
function get_data_fields($data,$option=[])
{
    if(!is_array($data)&& !is_array($option)){
        return false;
    }
    $new_arr=[];
    foreach ($data as $keies=>$value)
    {
        $arr ="";
        foreach ($option as $k){
            if(!isset($data[$k]))
            {
                continue;
            }
            if($keies === $k){
                $arr = $k;
            }
            continue;
        }
        if(empty($arr)){
            continue;
        }
        $new_arr[$arr] =$value ;
    }
    return $new_arr;
}


/**
 * 判断字段元素
 * @param $data
 * @param array $option
 * @return array|int
 */
function is_field_data($data,$option=[])
{
    if((!is_array($data)) && (!is_array($option))){
        return 0;
    }
    $new_arr =[];
    foreach ($option as $k){
        if(!empty($data[$k])){
            $new_arr[$k] = $data[$k];
        }
    }
    return $new_arr;
}

/**
 * @param $data
 * @return array
 */
function check_data($data)
{
    $new_data = [];
    foreach ($data as $k=>$v){
        if(!strlen($v)){
            continue;
        }
        $new_data[$k] = $v;
    }
    return $new_data;
}

/**
 * 替换键名
 * @param $param
 * @param $option
 * @return mixed
 */
function array_replace_key($param,$option)
{
    foreach ($param as $key=>$value){
        foreach ($option as $k=>$v){
            if($key == $k){
                unset( $param[$key]);
                $key = $v;
            }
        }
        $param[$key] = $value;
    }

    return $param;
}

/**
 * 生成随机数据
 * echo nRand('mail');die;
 * @param array $type
 * @param int $len
 * @return int|string
 */
function nRand($type=[],$len= 20)
{
    $t = gettype($type);
    if($t === 'string'){
        $rand="";
        if($type == 'phone')
        {
            $rand=0;
            for ($i = 1; $i < 9; ++$i) {
                $rand .=mt_rand(0, 9);
            }
            return (int) '13'.$rand;
        }elseif($type === 'mail'){
            $rand=0;
            for ($i = 1; $i < 9; ++$i) {
                $rand .=mt_rand(0, 9);
            }
            return '13'.$rand.'@'.$rand.'com';
        }else{
            for ($i = 0; $i < $len; $i++)
            {
                $rand .= chr(mt_rand(33, 126));
            }
            return  $rand;
        }

    }elseif ($t === "integer"){
        $rand='';
        $str = '0123456789';
        $max=strlen($str)-1;
        for ($i = 1; $i <= $len; ++$i) {
            $rand .=$str[mt_rand(0, $max)];
        }
        return $rand;
    }else{
        $rand = '';
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwsyz0123456789';
        $max=strlen($str)-1;
        for ($i = 1; $i <= $len; ++$i) {
            $rand .=$str[mt_rand(0, $max)];
        }
        return $rand;
    }

}


/**
 * TODO 时间比较
 * @param $begin_time
 * @param $end_time
 * @return array
 */
function timediff($begin_time,$end_time)
{
    if($begin_time < $end_time){
        $starttime = $begin_time;
        $endtime = $end_time;
    }else{
        $starttime = $end_time;
        $endtime = $begin_time;
    }

    //计算天数
    $timediff = $endtime-$starttime;
    $days = intval($timediff/86400);
    //计算小时数
    $remain = $timediff%86400;
    $hours = intval($remain/3600);
    //计算分钟数
    $remain = $remain%3600;
    $mins = intval($remain/60);
    //计算秒数
    $secs = $remain%60;
    $res = array("day" => $days,"hour" => $hours,"min" => $mins,"sec" => $secs);
    return $res;
}

/**
 * TODO 数据返回结构
 * @param $code
 * @param $msg
 * @param $data
 * @param $url
 * @param $wait
 * @return array
 */
function msg_array($code = 1,$msg="",$data=[],$url="",$wait= 3)
{
    $result = [
        'code' => $code,
        'msg'  => $msg,
        'data' => $data,
        'url'  => $url,
        'wait' => $wait,
    ];
    return $result;
}

/**
 * 获取服务器创建数据后的 结果数据集
 * 然后直接返回 前台数据格式
 * @param $data
 * @return array
 */
function service_create_return($data)
{
    if($data['code']){
        return msg_array(1,$data['msg'],$data['data']['status']);
    }else{
        return msg_array(0,$data['msg']);
    }
}


/**
 * Todo call_user_func_array
 * @param $method
 * @param array $arr
 * @return mixed
 */
function callFunc($method,$arr=[])
{
    return call_user_func_array($method,$arr);
}

/**
 *  Todo 查询start ===================================
 * 传入对象
 * 返回时间区间对象 between
 * @param int $y
 * @param int $m
 * @param int $d
 * @param $pdo
 * @return int
 */
function whereToYmd($y=0,$m=0,$d=0,$pdo)
{

    if($y && !$m && !$d)
    {
        $new_y = (int) $y +1;
        return $pdo->whereTime('create_time',"between",[date($y.'-01-01'.' 00:00:00'),date($new_y.'-01-01')]);
    }

    if($m && $y && !$d)
    {
        $new_m = (int) $m +1;
        $new_m_str = (string) $new_m;
        return $pdo->whereTime('create_time',"between",[date($y.'-'.$m.'-01'.' '.'00:00:00'),date($y.'-'.$new_m_str.'-01'.' '.'00:00:00')]);
    }
    if($d && $y && $m)
    {
        return $pdo->whereTime('create_time',"between",[date($y.'-'.$m.'-'.$d.' 00:00:00'),date($y.'-'.$m.'-'.$d.' 23:59:59')]);
    }

    return 0;//nothing
}

/**
 * Todo 查询  end
 * 返回时间区间数组 between
 * @param int $y
 * @param int $m
 * @param int $d
 * @return array|int
 */
function whereToYmdStr($y=0,$m=0,$d=0)
{

    if($y && !$m && !$d)
    {
        $new_y = (int) $y +1;
        return [date($y.'-01-01'.' 00:00:00'),date($new_y.'-01-01')];
    }

    if($m && $y && !$d)
    {
        $new_m = (int) $m +1;
        $new_m_str = (string) $new_m;
        return [date($y.'-'.$m.'-01'.' '.'00:00:00'),date($y.'-'.$new_m_str.'-01'.' '.'00:00:00')];
    }
    if($d && $y && $m)
    {
        return [date($y.'-'.$m.'-'.$d.' 00:00:00'),date($y.'-'.$m.'-'.$d.' 23:59:59')];
    }

    return 0;//nothing
}

/**
 * 验证是否存在或是否为true
 */
function is_not($val)
{
    $new_v= (string) $val;
    if(empty($val) || (!$val)){
        return false;
    }
    return true;
}

/**
 * 接受数据库表名称，去除前缀并转换为首字母大写，必须规范"x_y_z"
 * @param $str
 * @return string
 */
function table_str_func($str)
{
    $reg = '/^[a-zA-Z0-9]+_/';
    $t = preg_replace ($reg, "" , $str);
    $new_t = explode("_",$t);$new_str='';
    foreach ($new_t as $keies){
        $new_str .= ucfirst($keies);
    }
    return $new_str;
}

/**
 * 跨域
 * @param $boolean boolean true/false
 */
function not_region($boolean)
{
    $sumDomain = config('CrossDomain')['domain'];
    if($boolean){
        header('Access-Control-Allow-Origin:' . '*');
        header('Access-Control-Allow-Methods: GET,POST,OPTIONS,DELETE,PUT');
        header('Access-Control-Allow-Headers: Origin,Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
        if(isset($_SERVER['HTTP_ORIGIN'])){
            if(!in_array($_SERVER['HTTP_ORIGIN'],$sumDomain)){
                header("HTTP/1.1 404 Not Found");exit;
            };
        };

    }
}

/**
 * @param $boolean
 */
function region($boolean)
{
    if($boolean){
        header('Access-Control-Allow-Origin:*');
//        header('Access-Control-Allow-Origin:' . '*');
        header('Access-Control-Allow-Methods: GET,POST,OPTIONS,DELETE,PUT');
        header('Access-Control-Allow-Headers: Origin,Content-Type, Accept, Authorization, X-Request-With');
        header('Access-Control-Allow-Credentials: true');
    }
}

/**
 * @param $text
 * @return string
 */
function yd_error($text)
{
    return '<-- YADAN --> '.$text;
}

//注销不需要的数组字段
/**
 * @param array $data
 * @param string $field
 * @return array
 */
function unset_field($data=[],$field = ''){
    $new_data = $data;
    $field_arr = explode(',',$field);
    if(!count($data) || !count($field_arr)){
        return $data;
    }

    foreach ($field_arr as $value){
        unset($data[$value]);
    }

    return $new_data;
}

/**
 *生成随机数，可用户验证码
 *@param
 */
function randStr($m = 5) {
    $new_str = '';
    $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwsyz0123456789';
    $max=strlen($str)-1;
    for ($i = 1; $i <= $m; ++$i) {
        $new_str .=$str[mt_rand(0, $max)];
    }
    return $new_str;
}

//搜索解析
function grid_search_param_fund($params)
{
    $where = [];
    if(!$params) return $where;
    $param = json_decode($params);
    $type = $param->groupOp or $rule = [];
    $rule = $param->rules or $rule = [];

    foreach ($rule as $key=>$value){
        if($value->op === true || $value->op !== 'eq'){
            $ops = 'like';
            $types = "%".$value->data.'%';
            $where[$value->field] = [$ops,$types,$type];
        }else{
            $where[$value->field] = [$value->op,$value->data,$type];
        }

    }
    return $where;
}

/**
 * 日期查找
 * @param string       $field 日期字段名
 * @param string|array $op    比较运算符或者表达式
 * @param  string|array $range 比较范围
 * @return $this
 */
function whereTimeArr($field, $op, $range = null)
{
    if (is_null($range)) {
        if (is_array($op)) {
            $range = $op;
        } else {
            // 使用日期表达式
            switch (strtolower($op)) {
                case 'today':
                case 'd':
                    $range = ['today', 'tomorrow'];
                    break;
                case 'week':
                case 'w':
                    $range = ['this week 00:00:00', 'next week 00:00:00'];
                    break;
                case 'month':
                case 'm':
                    $range = ['first Day of this month 00:00:00', 'first Day of next month 00:00:00'];
                    break;
                case 'year':
                case 'y':
                    $range = ['this year 1/1', 'next year 1/1'];
                    break;
                case 'yesterday':
                    $range = ['yesterday', 'today'];
                    break;
                case 'last week':
                    $range = ['last week 00:00:00', 'this week 00:00:00'];
                    break;
                case 'last month':
                    $range = ['first Day of last month 00:00:00', 'first Day of this month 00:00:00'];
                    break;
                case 'last year':
                    $range = ['last year 1/1', 'this year 1/1'];
                    break;
                default:
                    $range = $op;
            }
        }
        $op = is_array($range) ? 'between' : '>';
    }
    return [$field=>[strtolower($op) . ' time',$range]];
}

/**
 * 验证日期格式
 * @param date
 * @return {boolean}
 */
function checkDateFormat($date,$type='ymdhis')
{
    //匹配日期格式 2016-02-16 23:59:59
    $new_date = ($type == 'ymdhi')? ($date.':00') :$date;
    $time = $new_date;
    $patten = "/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])\:(0?[0-9]|[1-5][0-9]))?$/";
    preg_match($patten,$time,$mat);
    if($type === 'ymdhis' || $type === 'ymdhi'){
        if(count($mat) !== 7){ //验证年月日时分秒
            return false;
        };
    }elseif($type === 'ymd'){
        if(count($mat) !== 3){ //验证年月日
            return false;
        };
    }else{
        return false;
    }

    if (preg_match ( $patten, $time )) {
        $timestro = strtotime ( $time );
        if($timestro) return $timestro;
        else return false;
    } else {
        return false;
    }
}

//是否当月
// $time 时间戳
//$type true 非时间戳 false 是时间戳
//$date_type month week day
//$rangUNIX false 返回开始与结束时间，用于区间查询
/**
 * @param int/string $time 时间参数
 * @param bool $type 是否为字符时间
 * @param string $date_type 日期类型
 * @param bool $rangUNIX 判断是否返回数组形式开始与结束
 * @param array $optionTime 完全自定义时间
 * @return array|bool
 */
function is_this_MWD($time,$type = false,$date_type = 'day',$rangUNIX = false,$optionTime = [])
{
    $new_time = $type?(strtotime($time)?:die(__METHOD__.'-time error!')):$time;
    switch (trim($date_type)){
        case 'month':
            $beginThis = mktime(0,0,0,date('m'),1,date('Y'));
            $endThis = mktime(23,59,59,date('m'),date('t'),date('Y'));
            break;
        case 'week':
            $beginThis=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
            $endThis=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
            break;
        case 'day':
            $beginThis=mktime(0,0,0,date('m'),date('d'),date('Y'));//时间的当天
            $endThis=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
            break;
        case 'some_day':
            $beginThis=mktime(0,0,0,date('m',$new_time),date('d',$new_time),date('Y',$new_time));//时间的当天
            $endThis=mktime(0,0,0,date('m',$new_time),date('d',$new_time)+1,date('Y',$new_time))-1;
            break;
        case 'some_week':
            $beginThis=mktime(0,0,0,date('m',$new_time),date('d',$new_time)-date('w',$new_time)+1-7,date('Y',$new_time));
            $endThis=mktime(23,59,59,date('m',$new_time),date('d',$new_time)-date('w',$new_time)+7-7,date('Y',$new_time));
            break;
        case 'some_month':
            $beginThis = mktime(0,0,0,date('m',$new_time),1,date('Y',$new_time));
            $endThis = mktime(23,59,59,date('m',$new_time),date('t',$new_time),date('Y',$new_time));
            break;
        case 'some_hour':
            $beginThis = mktime(date('H',$new_time),0,0,date('m',$new_time),date('d',$new_time),date('Y',$new_time));
            $endThis = mktime(date('H',$new_time),59,59,date('m',$new_time),date('d',$new_time),date('Y',$new_time));
            break;
        case '__some':
            if(count($optionTime) !== 2)die(__METHOD__.'-time error!,$optionTime length not 2');
            if((!$start = strtotime($optionTime[0])) || (!$end = strtotime($optionTime[1]))){
                die(__METHOD__.'-time error!,$optionTime arg not is (Y-m-d H:i:s)');
            }
            $beginThis = mktime(date('H',$start),date('i',$start),date('s',$start),date('m',$start),date('d',$start),date('Y',$start));
            $endThis = mktime(date('H',$end),date('i',$end),date('s',$end),date('m',$end),date('d',$end),date('Y',$end));
            break;
        default:
            return false;
    }
    if($rangUNIX) return ($endThis && $beginThis) ?[$beginThis,$endThis]:[];
    return $new_time > $beginThis && $new_time < $endThis;
}

//生成抽奖号数量 $money 金额  $num 区分的定额
/**
 * @param $money
 * @param $num
 * @return array
 */
function number_generate($money,$num)
{
    if ($money < $num) return [];
    $money_num = (INT) floor(round($money,2)/$num);//数量
    $money_remainder = round($money,2) - $money_num *$num;//求余

    if ($money_remainder == 0) $s = 0;
    else $s = -1;
    $data = [];
    for($v = $s; $v<$money_num;$v++){
        if(($money_num-1)==$v && $money_remainder) {
            $data[$v+1] = $money_remainder;continue;
        }
        $data[$v+1] = $num;
    }
    return $data;
}

//生成抽奖号数量 $money 金额  $num 区分的定额
/**
 * @param $money
 * @param $num string 金额基数
 * @return array
 */
function number_generate_new($money,$num)
{
    if ($money < $num) return [];
    $money_num = (INT) floor(round($money,2)/$num);//数量
    $s = 0;
    $data = [];
    for($v = $s; $v<$money_num;$v++){
        $data[$v] = $num;
    }
    return $data;
}


//hidden phone 隐藏手机号中间四位
/**
 * @param $data
 * @param string $phone_key_name
 * @return array
 */
function hidden_phone($data,$key_name ='',$type = 'phone')
{
    if(count($data)){
        $new_data= [];
        foreach ($data as $key=>$value)
        {
            if(!isset($value[$key_name])){
                return $data;
//                die(__METHOD__ .'-Phone Feild Undefine !');
            }

            if($type === 'date'){ //等于时间的时候，处理时间
                $value[$key_name] = date('Y-m-d H:i:s',$value[$key_name]);
                $new_data[] = $value;
            }else{
                $value[$key_name] = base_hidden_phone($value[$key_name]);
                $new_data[] = $value;
            }

        }
        return $new_data;
    }
    return $data;
//    die(__METHOD__ .'-Data Is Not Array ERROR !');
}

/**
 * @param $phone
 * @return mixed
 */
function base_hidden_phone($phone)
{
    return substr_replace($phone,'****',3,4);
}

/**模拟post进行url请求
 * @param string $url
 * @param array $post_data
 * @return bool|mixed
 */
function request_post($url = '', $post_data = array()) {

    if (empty($url) || empty($post_data)) {
        return false;
    }

    $o = "";
    foreach ( $post_data as $k => $v )
    {
        $o.= "$k=" . urlencode( $v ). "&" ;
    }
    $post_data = substr($o,0,-1);

    $postUrl = $url;
    $curlPost = $post_data;
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch);//运行curl
    curl_close($ch);

    return $data;
}
//用于抽奖测试查询日志
/**
 * @param $data
 * @param array $dataKeyName
 */
function arrayToExcel($data,$dataKeyName=[]){
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('firstsheet');
    $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
    $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
//add data
    $i = 1;
    foreach ($data as $line){
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$i, $line[$dataKeyName[0]]);
//        $objPHPExcel->getActiveSheet()->getCell('A'.$i)->setDataType('n');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, $line[$dataKeyName[1]]);
//        $objPHPExcel->getActiveSheet()->getCell('B'.$i)->setDataType('n');
        $i++;
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $path = RUNTIME_PATH.DS.'DRAW_PRIZE_EXL'.DS;
    if(!file_exists($path)) mkdir($path, 0700);
    $file = $path.'excel_'.time().'.xls';
    $objWriter->save($file);
}

/**
 * 创建(导出)Excel数据表格
 * @param  array   $list 要导出的数组格式的数据
 * @param  string  $filename 导出的Excel表格数据表的文件名
 * @param  array   $header Excel表格的表头
 * @param  array   $index $list数组中与Excel表格表头$header中每个项目对应的字段的名字(key值)
 * 比如: $header = array('编号','姓名','性别','年龄');
 *       $index = array('id','username','sex','age');
 *       $list = array(array('id'=>1,'username'=>'YQJ','sex'=>'男','age'=>24));
 * @return [array] [数组]
 */
function createtable($list,$filename,$header=array(),$index = array())
{
    header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition:filename=".$filename.".xls");
    $teble_header = implode("\t",$header);
    $strexport = $teble_header."\r";
    foreach ($list as $row){
        foreach($index as $val){
            $strexport.=$row[$val]."\t";
        }
        $strexport.="\r";

    }
    $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport);
    exit($strexport);
}

/**
 * @param $file
 * @param string $type]
 */
function get_require_once_database($file,$table = null,$type =EXT )
{
    $new_connect = require_once(APP_PATH.$file.$type);
    if(!empty($new_connect)){
        $db = \think\Db::connect($new_connect);
        if(!$table) return $db;
        return $db->table($table);
    }
    return 0;
}

/**
 * @param $url
 * @return bool
 */
function check_url($url)
{
    if(filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED))
        return true;
    else
        return false;
}

/**
 * 默认允许上传大小为20KB
 * @param $filename
 * @return array|\think\response\Json
 */
function inUpload($filename,$req_name,$rule=['size'=>163840]){
    $file = request()->file($req_name);
    if(!$file->check($rule)){
        return msg_array(0,'file size error!',[]);
    }
    $info = $file->move(IMG_UPLOAD.$filename);
    if(!$info){
        return msg_array(0,'file error',[]);
    }
    return msg_array(1,'success',['url'=>'static/uploads/'.$filename.'/'.$info->getSaveName()]);
}

/**
 * 删除静态文件
 * @param $url
 * @return bool
 */
function delfile($url)
{
    if( file_exists($url) && is_file( $url ) )
    {
        if( unlink( $url ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

/**
 * 删除指定文件
/**
 * @param $fileName
 * @param $pathAlias
 */
function df($fileName,$type="find",$pathAlias = 'public'){
    if(!empty($fileName)){
        if(DIRECTORY_SEPARATOR == '\\'){
            $path = str_replace('/',DIRECTORY_SEPARATOR,ROOT_PATH.$pathAlias.DS.$fileName);
        }else{
            $path = str_replace('\\',DIRECTORY_SEPARATOR,ROOT_PATH.$pathAlias.DS.$fileName);
        }
        if($type === 'select'){ //fixme 删除目录正则匹配的文件
            preg_match('/^E:\\'.DS.'(\w+\\'.DS.')+/i',$path,$var);//匹配目录
            preg_match('/(\w+)[.]jpg$/i',$fileName,$name);//匹配名称
            dirListFile($var[0],$name[1],['del'=>true]);//获取图片目录
        }elseif($type === 'find'){//fixme 删除单个文件
            @delfile($path);
        }else{
            //...';
        }
    }
}

/**
 * 获取文件目录并删除
 * @param $fileName //目录
 * @return string
 */
function dirListFile($fileName,$imageName,$option = []){
    $handler = opendir($fileName);$arr = [];
    while( ($filename = readdir($handler)) !== false )
    {
        if($filename === '.' || $filename === '..') continue;
        //$name[1] = 1535699889
        if(!preg_match('/'.$imageName.'/i',$filename))continue; //匹配图片名称
        if(isset($option['del'])){ //当定义del参数时，删除匹配项
            $newPath = $fileName.$filename;
            @delfile($newPath);//删除同前缀的图片文件：如：1535699889_350x237.jpg && 1535699889.jpg
        }
    }
    return $arr;
}

/** 生成二维码
 * @param $generateStr string 生成的字符串
 * @param $fileName string 文件名称
 * @param $path string 生成的路径
 */
function generateQrCode($generateStr,$fileName,$path = ROOT_PATH.'public'.DS.'qrcode'.DS.'uploads'.DS)
{
    if(!file_exists($path))
    {
        //Check to see if there is a folder, if not created, and given the maximum permissions
        mkdir($path, 0700,true);
    }
    $codes = new \Endroid\QrCode\QrCode($generateStr);
    header('Content-Type: '.$codes->getContentType());
    $codes->writeFile($path.$fileName.'.png');
}