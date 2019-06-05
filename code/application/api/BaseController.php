<?php

namespace api;

///  Explanation: This is a light-weight alternative to having Zend do routing.
///    The difference is speed...  LOTs of Speed!
///
///  How fast?
///
///     Super-Flat Zend (w/ DB connect): 60 - 120 msec
///     QuickServe      (w/ DB connect): 10 - 15 msec
///     QuickServe     (w/o DB connect): 8 - 13 msec
///

class BaseController { //  No Extends, we are not using Zend...  See QuickServeAPIs.php

	public function init() {
		$this->params = $_REQUEST;
		$this->requireAuth();
		return $this;
	}

	public function standardReturnObject($add_array = []) {
		return (object) array_merge(array(
			'error' => null,
			), (array) $add_array);
	}

	public function standardReturnObject__debuggable($return, $force = false) {
    	if ( $GLOBALS['BUG_ON'] && ( ! isset( $_SERVER['HTTP_ACCEPT'] )
    		|| ( strpos(    $_SERVER['HTTP_ACCEPT'], 'application/json') === false
    			&& strpos( $_SERVER['HTTP_ACCEPT'], 'text/javascript')  === false
    			)
    		|| $force
    		 ) ) {
    		echo '<xmp>'.safe_json_encode($return) .'</xmp>';
	    	report_timers();
	    	\App::report_queries();
	    	exit;
	    }

        return $this->standardReturnObject($return);
	}

	public function requireAuth($redir = null) {
		// $user = App::adminCheckAuth();

		// if ( ! empty( $user ) ) {
		// 	$this->auth_user = $user;
		// }
		// else {
		// 	$this->forbidden('Access Denied');
		// }

		// return $user;
		return true;
	}

	public function actionReturnTransport($returned_stuff) {
    	if ( is_string( $returned_stuff ) ) { echo $returned_stuff; }
    	else { 
    		header('Content-Type: application/json');
    		echo ")]}',\n". /* <-- Angular-Style JSONP */ safe_json_encode( $returned_stuff );
    	}
		session_write_close();
    	exit;
    }

	public function fault(     $errmsg) { throw new Exception("API Error: ". $errmsg); }
	public function notFound(  $errmsg) { header("HTTP/1.0 404 Not Found");   echo $this->actionReturnTransport($errmsg); }
	public function badRequest($errmsg) { header("HTTP/1.0 400 Bad Request"); echo $this->actionReturnTransport($errmsg); }
	public function forbidden($errmsg = '') { header("HTTP/1.0 403 Forbidden"); echo "<h1>Forbidden</h1>".$errmsg; exit; }

	public static $__rawRequestFH = array('-1'); // This value means "Not reaad yet"
	public function getRawRequestFH() {
		if ( is_array(BaseController::$__rawRequestFH) && BaseController::$__rawRequestFH[0] === '-1' ) { // This value means "Not reaad yet"
			BaseController::$__rawRequestFH = fopen("php://input",'r');
		}
		return BaseController::$__rawRequestFH;
	}
	public static $__rawRequest = array('-1'); // This value means "Not reaad yet"
	public function getRawRequest() {
		if ( is_array(BaseController::$__rawRequest) && BaseController::$__rawRequest[0] === '-1' ) { // This value means "Not reaad yet"
			BaseController::$__rawRequest = stream_get_contents($this->getRawRequestFH());
		}
		return BaseController::$__rawRequest;
	}
	public $__getJSONRequest = null;
	public function getJSONRequest() {
		if ( is_null( $this->__getJSONRequest ) ) {
			///  Read params
			$this->__getJSONRequest = json_decode($this->getRawRequest());
		}
		return $this->__getJSONRequest;
	}
    public function readSlashURLParams() {
    	// if ( ! preg_match('@^/api/[\w\-]+/[\w\-]+/([^\?]+)@', $_SERVER['REQUEST_URI'], $m ) ) { return false; }
    	if ( empty($this->path_info) ) { return false; }
    	$params = explode('/', trim($this->path_info,'/'));
    	foreach ( $params as $i => $x ) { $params[$i] = urldecode($params[$i]); }
    	return $params;
    }

    public function byMethod($__function__) {
    	$method = $__function__ .'__'. $_SERVER['REQUEST_METHOD'];
    	if ( ! method_exists($this, $method) ) { return $this->fault('Method Not Implemented'); }
    	return $this->$method();
    }

}
