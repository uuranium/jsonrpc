<?php

class UrlHelper
{

    /**
     * @return string
     */
    public static function getCurrentUrl(): string
    {
        $url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return str_replace("&", "&amp;", $url);
    }
}