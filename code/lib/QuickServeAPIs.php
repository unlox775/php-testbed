<?php

///  Explanation: This is a light-weight alternative to having Zend do routing.
///    The difference is speed...  LOTs of Speed!
///
///  How fast?
///
///     Super-Flat Zend (w/ DB connect): 60 - 120 msec
///     QuickServe      (w/ DB connect): 10 - 15 msec
///     QuickServe     (w/o DB connect): 8 - 13 msec
///

class QuickServeApis {
	public $url_base = null;
	public $ctl_base = null;
	public $class_prefix = '';
	public function __construct($url_base, $ctl_base, $class_prefix = '') {
		$this->url_base = $url_base;
		$this->ctl_base = $ctl_base;
		$this->class_prefix = $class_prefix;
	}

	public function bootstrap() {
	// 	require_once( $this->ctl_base .'/Bootstrap.php' );
		return $this;
	}

	public function run() {
		///  Parse REQUEST_URI into a Class / Action Name
		$path_regex = ( 
			'@^/\Q'. trim($this->url_base) .'\E/'
			. '([\w\-\_]+)'
			. '(?:/([a-zA-Z_][\w\-\_]*))?' // Actions cannot start with numeric, this is how we allow index actions with numeric IDs passed
			. '(\W[^\?]*)?'
			. '(\?|$)@'
			);

		if ( ! preg_match($path_regex, $_SERVER['REQUEST_URI'], $m) ) { header("HTTP/1.0 404 Not Found"); echo "404 Not Found\n</br>Api QuickServe";  exit; }
		@list($x,$flat_class,$flat_action,$path_info) = $m;
		if ( empty( $flat_action ) && ! is_numeric($flat_action) ) { $flat_action = 'index'; }

		$class = self::flatToCamelCase($flat_class .'-controller');
		$full_class = $this->class_prefix . $class;
		$action = self::flatToCamelCase($flat_action .'-action');

		///  Check file
		$class_file = $this->ctl_base .'/'. $class .'.php';
		if ( ! file_exists($class_file) ) { header("HTTP/1.0 404 Not Found"); echo "404 Not Found\n</br>Api QuickServe (Class File Not Found $full_class)";  exit; }

		///  Check Class
		@include($this->ctl_base .'/'. $class .'.php');
		if ( ! class_exists($full_class) ) { header("HTTP/1.0 404 Not Found"); echo "404 Not Found\n</br>Api QuickServe (Class Not Found, Misnamed or Syntax Error)";  exit; }

		///  Check Action Method
		$ctl = new $full_class();
		if ( ! method_exists($ctl, $action) && ! method_exists($this->class_prefix . $class, '__call') ) { header("HTTP/1.0 404 Not Found"); echo "404 Not Found\n</br>Api QuickServe (Action Not Found, Misnamed or Syntax Error)";  exit; }

		$ctl->controller = $flat_class;
		$ctl->action = $flat_action;
		$ctl->path_info = $path_info;

		///  Run init()
		if ( method_exists($ctl, 'init') ) { $ctl->init(); }

		///  Run Action
		$result = $ctl->$action();

		///  Default Transport ( if they returned non-empty and didn't exit )
		if ( ! empty( $result ) && method_exists($ctl, 'actionReturnTransport') ) {
			$ctl->actionReturnTransport($result);
		}

		return $this;
	}

	public static function flatToCamelCase($flat) {
		return preg_replace_callback('/(?:^|[\-|_])(.?)/',function($s){ return strtoupper($s[1]); },$flat);
	}
	public static function camelCaseToFlat($camel) {
		return ltrim(strtolower(preg_replace('/([A-Z])/','_$1',$camel)),'-');
	}
}
