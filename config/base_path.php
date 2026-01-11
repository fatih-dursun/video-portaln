<?php
// Base Path Helper
define('BASE_PATH', '/video-portal/public');

function asset($path) {
    return BASE_PATH . $path;
}

function url($path = '') {
    return BASE_PATH . $path;
}