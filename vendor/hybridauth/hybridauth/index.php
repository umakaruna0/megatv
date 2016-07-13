<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
global $USER;

session_start();
$config = $_SERVER['DOCUMENT_ROOT'].'/vendor/hybridauth/hybridauth/hybridauth/config.php';

$provider_name = $_REQUEST["provider"];

try{
    $hybridauth = new Hybrid_Auth( $config );
	$adapter = $hybridauth->authenticate( $provider_name );
	$userProfile = $adapter->getUserProfile();
    $userProfile = (array)$userProfile;
}
catch( Exception $e )
{
    $userProfile = NULL;
    //$adapter->logout();
    LocalRedirect("/?login=Y&social-error=".$e->getMessage());
    die();
}

$USER_ID = CSocialAuth::getUserByProviderAndId($provider_name, $userProfile);
if(!$USER_ID)
{
    $USER_ID = CSocialAuth::createUser($provider_name, $userProfile);
}

if($USER_ID)
{
    //echo "<pre>"; print_r($userProfile); echo "</pre>";die();
    $USER->Authorize($USER_ID, true);
    LocalRedirect("/");
}
die();
?>