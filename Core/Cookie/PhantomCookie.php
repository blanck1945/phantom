<?php

namespace Core\Cookie;

class PhantomCookie
{
    public function set_cache_cookie($value, $time = 36000)
    {
        // JSON encode the value
        $value = json_encode($value);

        setcookie('page_cache', $value, time() + $time, '/');
    }
}
