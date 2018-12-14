<?php namespace App\Services;

use Session, Request, Cookie;

class SC {

    /**
     * 用户登录的session key
     */
    CONST LOGIN_MARK_SESSION_KEY = 'LOGIN_MARK_SESSION';

    /**
     * 密钥 session key
     */
    CONST PUBLIC_KEY = 'LOGIN_PROCESS_PUBLIC';

    /**
     * TGC的cookie的名字
     */
    CONST TGC_COOKIE_KEY = 'TGC';

    /**
     * 设置登录成功的session
     * 
     * @param array $userInfo 用户的相关信息
     */
    static public function setLoginSession($userInfo)
    {
        return Session::put(self::LOGIN_MARK_SESSION_KEY, $userInfo);
    }

    /**
     * 返回登录成功的session
     */
    static public function getLoginSession()
    {
        return Session::get(self::LOGIN_MARK_SESSION_KEY);
    }

    /**
     * 删除登录的session
     * @see \Illuminate\Session\Store
     * @return void
     */
    static public function delLoginSession()
    {
        /**
         * @see  \Illuminate\Session\Store::forget
         */
        Session::forget(self::LOGIN_MARK_SESSION_KEY);
        /**
         * @see \Illuminate\Session\Store::flush
         */
        Session::flush();
        /**
         * @see \Illuminate\Session\Store::regenerate
         */
        Session::regenerate();
    }

    /**
     * 设置并返回加密密钥
     *
     * @return string 密钥
     */
    static public function setPublicKey()
    {
        $key = uniqid();
        Session::put(self::PUBLIC_KEY, $key);
        return $key;
    }

    /**
     * 取得刚才设置的加密密钥
     * 
     * @return string 密钥
     */
    static public function getPublicKey()
    {
        return Session::get(self::PUBLIC_KEY);
    }

    /**
     * 删除密钥
     * 
     * @return void
     */
    static public function delPublicKey()
    {
        Session::forget(self::PUBLIC_KEY);
        Session::flush();
        Session::regenerate();
    }

    /**
     * 设置TGC的cookie
     */
    static public function setTgcCookie($value)
    {
        return Cookie::queue(self::TGC_COOKIE_KEY, $value);
    }

    /**
     * 返回设置好的TGC
     */
    static public function getTgcCookie()
    {
        return Request::cookie(self::TGC_COOKIE_KEY);
    }

    /**
     * 删除tgc
     * 
     * @return void
     */
    static public function delTgcCookie()
    {
        return Cookie::queue(self::TGC_COOKIE_KEY, '', time() - 3600);
    }

}