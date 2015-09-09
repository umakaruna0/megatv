<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
global $USER;

$config = dirname(__FILE__) . '/hybridauth/config.php';
require_once($_SERVER["DOCUMENT_ROOT"]."/social/hybridauth/Hybrid/Auth.php" );

$provider_name = $_REQUEST["provider"]; 
try{
    // initialize Hybrid_Auth class with the config file
    $hybridauth = new Hybrid_Auth( $config );
	$adapter = $hybridauth->authenticate( $provider_name );
	$userProfile = $adapter->getUserProfile();
    $userProfile = (array)$userProfile;
    //echo "<pre>"; print_r($userProfile); echo "</pre>";
    
    //die();
}
catch( Exception $e )
{
    echo $e->getMessage();
    die();
    LocalRedirect("/?login=Y&social-error=".$e->getMessage());
}

$USER_ID = CSocialAuth::getUserByProviderAndId($provider_name, $userProfile);
if(!$USER_ID)
{
    $USER_ID = CSocialAuth::createUser($provider_name, $userProfile);
}

if($USER_ID)
{
    $USER->Authorize($USER_ID);
    LocalRedirect("/");
}
die();
?>