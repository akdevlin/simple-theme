<?php
//set constant in config.php to enable this debugging feature
//define('SAVEQUERIES', true);
//generic printer for troubleshooting
function ad_printer($something = "Testing, 123") {
    if (current_user_can('manage_options')) { //only shows this debugging feature if the user has the baility to manage options
        $ad_baseStyle = 'overflow-wrap:break-word;word-break:break-all;padding:10px;word-wrap:break-word;font-size:16px; display:block!important;font-family:monospace; font-weight:bold;';
        $tan_red = $ad_baseStyle . 'color:maroon; background-color:#ffdead;border:2px solid maroon;';
        $black_white = $ad_baseStyle . 'color:white; background-color:black;border:2px dashed black;';
        $yellow_green = $ad_baseStyle . 'color:gold; background-color:seagreen;border:2px dashed yellow;';
        $gray_blue = $ad_baseStyle . 'color:slateblue; background-color:silver;border:2px dashed yellow;';
        $black_yellow = $ad_baseStyle . 'color:gold; background-color:black;border:3px dotted black;';
        $adDebugStyle = $gray_blue;
        echo '<pre style="' . $adDebugStyle . '">';
        print_r($something);
        echo '</pre>';
    }
}