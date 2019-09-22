<?php

defined('ASSETS_VERSION') || define('ASSETS_VERSION', '201812241736');

 
function proxy_asset($src, $width = null, $height = null)
{
    if (preg_match('/^http/', $src)) {
        return $src;
    }
    $src = config('app.qiniu_blog_proxy') . $src;
    $tail = '';
    if ($width && $height) {
        $tail = 'imageView2/2/w/' . $width . '/h/' . $height . '/interlace/1/q/75|imageslim';
    }
    return $src . '?' . $tail;
}