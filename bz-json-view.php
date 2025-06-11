<?php
/*
Plugin Name: BZ JSON Viewer
Description: Отображает JSON через шорткод [bz-json src='example.json'], файлы json должны находиться в папке json плагина
Version: 1.2
Author: BZ
*/

function color_span($color, $content) {
    return "<span style=\"color:$color;\">$content</span>";
}

function render_null($null) {
    return color_span('purple', 'NULL');
}

function render_bool($bool) {
    return color_span('blue', ['true', 'false'][$bool]);
}

function render_num($num) {
    return color_span('red', $num);
}

function render_string($str) {
    return color_span('green', $str);
}

function render_array($arr) {
    return color_span('gray', 'ARRAY') . '<ol start="0">' .
           implode('', array_map(fn($val) => "<li>" . render_json($val) . "</li>", $arr)) .
           '</ol>';
}

function render_object($obj) {
    return color_span('gray', 'OBJECT') . '<ul>' .
           implode('', array_map(fn($key, $val) => "<li><span>$key</span>: " . render_json($val) . "</li>", array_keys($obj), $obj)) .
           '</ul>';
}

function render_json($val) {
    return match (true) {
        is_array($val) && array_is_list($val)  => render_array($val),
        is_array($val)                         => render_object($val),
        is_null($val)                          => render_null(),
        is_bool($val)                          => render_bool($val),
        is_numeric($val)                       => render_num($val),
        default                                => render_string($val),
    };
}

function bz_json_view_shortcode($atts) {
    return '<div>' . 
        render_json(
            json_decode(
                file_get_contents(__DIR__ . '/json/' . ltrim(($atts['src'] ?? 'default.json'), '/')), true
            )
        ) . 
    '</div>';
}

add_shortcode('bz-json', 'bz_json_view_shortcode');