<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Helpers\Constant;
use Spatie\Permission\Models\Role;

class Method {
    // common
    function left($str, $length)
    {
        return substr($str, 0, $length);
    }

    function right($str, $length)
    {
        return substr($str, -$length);
    }
    // end common

    // app
    // end app
}