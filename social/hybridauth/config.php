<?php
/**
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return
	array(
		"base_url" => "http://".$_SERVER["SERVER_NAME"]."/social/hybridauth/",

		"providers" => array (
        
			"Facebook" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "430553963782834", "secret" => "a76b9d30649b3b8cb94abdf76a192c48" ),
				'scope'   => "email, user_about_me, user_birthday",
                'trustForwarded' => false,
                //"display" => "popup" 
			),
            "Vkontakte" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "5038690", "secret" => "23rp2VzurZaMHoRks2Ak" ),
                'trustForwarded' => false,
                //"display" => "popup" 
			),
            
		),

		"debug_mode" => false,

		// Path to file writable by the web server. Required if 'debug_mode' is not false
		"debug_file" => "",
	);
