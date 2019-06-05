<?php

$GLOBALS['PHP_START_microtime'] = microtime(true);
define('CODE_ROOT', realpath('../'));
define('APPLICATION_PATH', CODE_ROOT .'/application');
require_once( APPLICATION_PATH .'/config/Bootstrap.php');

///  Route 1 : Quick-Serve APIs
if ( strpos($_SERVER['REQUEST_URI'],'/api/') === 0 ) {
	Bootstrap::initSiteAPI();

	$application = new QuickServeAPIs('api',APPLICATION_PATH .'/api','api\\');
	try {
		$application->bootstrap()->run();
	}
	catch (Exception $e ) {
		// bug(); exit;
		if ( ! empty($GLOBALS['BUG_ON']) ) {
			echo (
				"<h1>Fatal: ". get_class($e) ."</h1>\n"
				. "<pre>". $e->getMessage() ."</pre>\n"
				. "<h2>Caller Trace:</h2>\n"
				. "<pre>". $e->getTraceAsString() ."</pre>\n"
				);
		}
		exit;
		header("HTTP/1.0 500 Internal Server Error"); echo "500 Internal Server Error\n</br>Application Error";  exit;
		// header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
	}
	exit;
}
?>
<body style="background: #f9f1e1;">
<table cellpadding=0 cellspacing=0 border=0 style="width: 100%; height: 100%">
	<tr>
		<td style="vertical-align: middle; text-align: center; font-size: 40px; font-style: italic; font-family: Trebuchet, Verdana, sans-serif; color: #21738e">
			How many Camels<br/> smoking Camels<br/> does it take to<br/> stir the Caramels?
		</td>
	</tr>
</table>
</body>
