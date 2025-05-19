<?php

if (!function_exists('build_cache_key')) {
    function build_cache_key(array $filters, string $prefix = 'cache_key'): string
    {
        ksort($filters);
        return $prefix . '_' . md5(json_encode($filters));
    }
}
