<?php
/**
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

/**
 * vk           https://vk.com/editapp?id=5063256
 * fb           https://developers.facebook.com/apps
 * Google +     https://code.google.com/apis/console/
 * Instagram    https://instagram.com/developer/clients/3e433be6d1a74c8b8b7799777bf42396/edit/
 */


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
    if(in_array(strtolower($arItem["PROPERTY_PROVIDER_VALUE"]), array("twitter", "linkedin")))
    {
        $key = "key";
    }else{
        $key = "id";
    }
    
    $arSocialConfig[$arItem["PROPERTY_PROVIDER_VALUE"]] = array(
        "enabled" => true,
		"keys"    => array ( 
            $key => $arItem["PROPERTY_ID_VALUE"], 
            "secret" => $arItem["PROPERTY_SECRET_VALUE"], 
        ),
        'trustForwarded' => false,
        "display" => "popup"
    );
    
    if(strtolower($arItem["PROPERTY_PROVIDER_VALUE"])!="instagram")
    {
        $arSocialConfig[$arItem["PROPERTY_PROVIDER_VALUE"]]["scope"] = "email, user_about_me, user_birthday";
    }
}


return
	array(
		"base_url" => "http://megatv.su/social/hybridauth/",
		"providers" => $arSocialConfig,
		"debug_mode" => false,
		"debug_file" => "/temp/hybridauth.log",
	);