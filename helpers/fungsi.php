<?php
function base_url($path = '') {
    return rtrim((isset($_SERVER['HTTP_HOST'])? 'http://'.$_SERVER['HTTP_HOST'] : ''), '/') . '/' . ltrim($path, '/');
}
