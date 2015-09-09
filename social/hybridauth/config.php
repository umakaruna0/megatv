<?php
/**
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

CModule::IncludeModule("iblock");
$arSocialConfig = array();
$arrFilter = array(
    "IBLOCK_ID" => SOCIAL_CONFIG_IB,
    "ACTIVE" => "Y",
    "!PROPERTY_SECRET" => false,
    "!PROPERTY_PROVIDER" => false,
    "!PROPERTY_ID" => false,
);
$arSelect = array("PROPERTY_PROVIDER", "PROPERTY_ID", "PROPERTY_SECRET");
$rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
while( $arItem = $rsRes->GetNext() )
{
    $arSocialConfig[$arItem["PROPERTY_PROVIDER_VALUE"]] = array(
        "enabled" => true,
		"keys"    => array ( 
            "id" => $arItem["PROPERTY_ID_VALUE"], 
            "secret" => $arItem["PROPERTY_SECRET_VALUE"], 
        ),
		'scope'   => "email, user_about_me, user_birthday",
        'trustForwarded' => false,
        "display" => "popup"
    );
}


return
	array(
		"base_url" => "http://".$_SERVER["SERVER_NAME"]."/social/hybridauth/",
		"providers" => $arSocialConfig,
		"debug_mode" => false,
		"debug_file" => "/temp/hybridauth.log",
	);