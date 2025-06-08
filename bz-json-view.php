<?php
/*
Plugin Name: BZ JSON Viewer
Description: Отображает JSON через шорткод [bz-json src='{"key": "value"}']
Version: 1.0
Author: BZ
*/

const DEFAULT_JSON = '{
  "name": "Mac",
  "age": 25,
  "rating": 4.5,
  "isActive": true,
  "isVerified": false,
  "metadata": null,
  "skills": ["PHP", "JavaScript", "WordPress"],
  "contact": {
    "email": "mac@example.com",
    "phone": "+7 999 123-45-67"
  }
}';


function render_null($null) {
    return '<span style="color:purple;">NULL</span>';
}

function render_bool($bool) {
    return '<span style="color:blue;">' . ['false', 'true'][$bool] . '</span>';
}

function render_num($num) {
    return '<span style="color:red;">' . $num . '</span>';
}

function render_string($str) {
    return '<span style="color:green;">' . $str . '</span>';
}

function render_array($arr) {
    return '<span style="color:gray;">ARRAY</span><ol start="0">' . 
           implode('', array_map(fn($key, $val) => '<li>' . render_json($val) . '</li>', array_keys($arr), $arr)) . 
           '</ol>';
}

function render_object($obj) {
    return '<span style="color:gray;">OBJECT</span><ul>' . 
           implode('', array_map(fn($key, $val) => '<li><span>' . $key . '</span>: ' . render_json($val) . '</li>', array_keys($obj), $obj)) . 
           '</ul>';
}

function render_json($val) {
    if (is_array($val) && array_is_list($val)) { return render_array($val); }
    if (is_array($val)) { return render_object($val); }
    if (is_null($val)) { return render_null($val); }
    if (is_bool($val)) { return render_bool($val); }
    if (is_numeric($val)) { return render_num($val); }
    return render_string($val);
}

function bz_json_view_shortcode($atts) {
    return '<div>' . render_json(json_decode($atts['src'] ?? DEFAULT_JSON, true)) . '</div>';
}

add_shortcode('bz-json', 'bz_json_view_shortcode');