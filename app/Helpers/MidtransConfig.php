<?php

namespace App\Helpers;

use Midtrans\Config;

class MidtransConfig
{
    public static function init()
    {
        Config::$serverKey = 'Mid-server-TA6HDpYixqV_R0bDgtjF1uO8'; // ← Ganti dengan server key Midtrans sandbox
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }
}
