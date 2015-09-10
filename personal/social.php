<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
global $USER;

$config = $_SERVER['DOCUMENT_ROOT']. '/social/hybridauth/config.php';
require_once($_SERVER["DOCUMENT_ROOT"]."/social/hybridauth/Hybrid/Auth.php" );

$provider_name = strtolower($_REQUEST["provider"]); 
try{
    $hybridauth = new Hybrid_Auth( $config );
	$adapter = $hybridauth->authenticate( $provider_name );
	$userProfile = $adapter->getUserProfile();
    $userProfile = (array)$userProfile;
    //echo "<pre>"; print_r($userProfile); echo "</pre>";
}
catch( Exception $e )
{
    $userProfile = NULL;
    $adapter->logout();
    
    LocalRedirect("/?login=Y&social-error=".$e->getMessage());
}

CSocialAuth::connectToUser($USER->GetID(), $provider_name, $userProfile);
LocalRedirect("/personal/");
die();
?>