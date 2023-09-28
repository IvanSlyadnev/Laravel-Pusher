<?php

if (! function_exists('clear_phone')) {
    function clear_phone($phone)
    {
        if ($phone) {
            $phone = preg_replace('/\D+/', '', $phone);

            if (strlen($phone) && $phone[0] == '8') {
                $phone[0] = '7';
            }

            return $phone;
        }
    }
}

