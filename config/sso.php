<?php

return [

    //注册客户端
    'services' => ['s1' => 'http://localhost:19998', 's2' => 'http://localhost:19997', 's3' => 'http://www.debug.com', 's4' => 'http://192.168.3.99:6699'],

    //登录处理用哪个处理器来处理
    'login_process' => 'default'
];