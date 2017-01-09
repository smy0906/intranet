<?php

namespace Intra\Lib;

class DateUtil
{
    public static function isWeekend($date)
    {
        return (date('N', strtotime($date)) >= 6);
    }
}
