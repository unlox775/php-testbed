<?php

namespace api;

class TestController extends \api\BaseController {
	public function init(){

		parent::init();

		// if( !$this->auth_user->hasAccessTo( 'test-section' ) ) {
		// 	$this->forbidden( "You do not have access to this section." );
		// }
	}

	public function indexAction() { return $this->byMethod(__FUNCTION__); }
	public function indexAction__POST() {
		$params = $this->getJSONRequest();

		return $this->standardReturnObject(array(
        	'test' => 1,
        	'param_one' => empty($params->one) ? null : $params->one
        	));
	}
	public function indexAction__GET() {
		list($test_id) = $this->readSlashURLParams();

		return $this->standardReturnObject(array(
        	'test' => 2,
        	'param_first' => $test_id
        	));
	}
}
