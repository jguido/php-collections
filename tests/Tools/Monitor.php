<?php


namespace Tools;


class Monitor
{
    public static function monitor($function)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;
        $function();
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;
        $totaltime = ($endtime - $starttime);
        echo "\n";
        echo "Executed in ".$totaltime." seconds";
        echo "\n";
    }
}