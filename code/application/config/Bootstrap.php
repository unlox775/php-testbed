<?php

date_default_timezone_set('America/Boise');
error_reporting(E_ALL);
ini_set("display_errors",1);
require_once(dirname(__FILE__) .'/autoload.php');

class Bootstrap {

	public static function initSiteAPI() {
	}
}

if ( ! function_exists('safe_json_encode') ) { function safe_json_encode($value){
    if (version_compare(PHP_VERSION, '5.4.0') >= 0) {
        $encoded = json_encode($value, JSON_PRETTY_PRINT);
    } else {
        $encoded = json_encode($value);
    }
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            return $encoded;
        case JSON_ERROR_DEPTH:
            return 'Maximum stack depth exceeded'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_STATE_MISMATCH:
            return 'Underflow or the modes mismatch'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_CTRL_CHAR:
            return 'Unexpected control character found';
        case JSON_ERROR_SYNTAX:
            return 'Syntax error, malformed JSON'; // or trigger_error() or throw new Exception()
        case JSON_ERROR_UTF8:
            $clean = utf8ize($value);
            return json_encode($clean);
        default:
            return 'Unknown error'; // or trigger_error() or throw new Exception()

    }
} }

if ( ! function_exists('utf8ize') ) { function utf8ize($mixed) {
    if (is_array($mixed)) {
        foreach ($mixed as $key => $value) {
            $mixed[$key] = utf8ize($value);
        }
    }else if (is_object($mixed)) {
        foreach ((array) $mixed as $key => $value) {
        	if ( substr($key,0,1) == "\0" ) { continue; }
            $mixed->$key = utf8ize($value);
        }
    } else if (is_string ($mixed)) {
        return utf8_encode($mixed);
    }
    return $mixed;
} }
