<?php
namespace Seven\Router;

if (!function_exists('redirect')) {
    function redirect($base_url, $location = '')
    {
        $location = $base_url . "/{$location}";
        if (!headers_sent()) {
            header("location: $location");
            exit();
        } else {
            echo "<script type='text/javascript'> window.location.href= '{$location}';</script>";
            echo '<noscript> <meta http-equiv="refresh" content="0;url=' . $location . '"/></noscript>';
            exit();
        }
    }
}

if (!function_exists('str_contains')) {
    function str_contains(string $str, $contain, bool $ignoreCase = false): bool
    {
        if ($ignoreCase) {
            $str = mb_strtolower($str);
        }
        $contain = is_array($contain) ? $contain : [$contain];
        foreach ($contain as $val) {
            $val = ($ignoreCase) ? mb_strtolower($val) : $val;
            if (mb_strpos($str, $val) !== false) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('str_between')) {
    function str_between(string $full, string $start, string $stop, bool $ignoreCase = false): string
    {
        if ($ignoreCase) {
            $full = mb_strtolower($full);
            $start = mb_strtolower($start);
            $stop = mb_strtolower($stop);
        }
        $start_pos = mb_strpos($full, $start);
        if ($start_pos === false) {
            return "";
        }
        $start_pos += mb_strlen($start);
        $length = mb_strpos($full, $stop, $start_pos) - $start_pos;
        return mb_substr($full, $start_pos, $length);
    }
}
