<?php

namespace App\Traits;

trait GetSize
{
    function sizeExplode($byte) {

        $kb = $byte / 1024;
        $mb = $kb / 1024;
        $gb = $mb / 1024;

        if ($mb >= 1) $result = round($mb, 1) . 'MB';
        else if ($gb >= 1) $result = round($gb, 1) . 'GB';
        else $result = round($kb, 1) . 'KB';

        return $result;
    }

}
