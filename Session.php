<?php

namespace azadkh\mvcframework;

class Session
{
    protected const FLASH_KEY = "flash_messages";
    public function __construct()
    {
        session_start();
        $flash_messages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flash_messages as $key => &$flash_message) {
            //Mark here for romoved
            $flash_message['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flash_messages;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'message' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['message'] ?? false;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        //remove marked 
        $flash_messages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flash_messages as $key => &$flash_message) {
            if ($flash_message['remove']) {
                unset($_SESSION[self::FLASH_KEY][$key]);
            }
        }
    }
}