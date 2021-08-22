<?php

class AdminCredentialsService {

    public $ibm_apikey = '';
    public $ibm_apiurl = '';

    function __construct( $ibm_apikey = null, $ibm_apiurl = null ){

		$ailouise_options = get_option( 'ailouise_option_name' );

		if( !$ibm_apikey ){
			$ibm_apikey = $ailouise_options['ibm_api_key'];
		}

		if( !$ibm_apiurl ){
			$ibm_apiurl = $ailouise_options['ibm_api_url'];
		}

        $this->ibm_apikey = $ibm_apikey;
        $this->ibm_apiurl = $ibm_apiurl;
		$this->setConsts();
    }

    public function setConsts(){
        define('IBM_APIKEY', $this->ibm_apikey);
        define('IBM_APIURL', $this->ibm_apiurl);
    }

}

if( is_admin() )
	new AdminCredentialsService();
