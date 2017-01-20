<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
global $USER;

$errcode = 0;

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
    $errcode = $e->getMessage();
    //LocalRedirect("/?login=Y&social-error=".$e->getMessage()); die();
}

if($errcode==0 && !empty($userProfile))
{
    $USER_ID = CSocialAuth::getUserByProviderAndId($provider_name, $userProfile);

    if(!$USER_ID)
    {
        $response = CSocialAuth::createUser($provider_name, $userProfile);
        
        //error
        if(!empty($response["error"]))
        {
            $errcode =  $response["error"];
        }else{
            $USER_ID = $response["ID"];
        }
    }
    
    if(intval($USER_ID)>0)
    {
        //echo "<pre>"; print_r($userProfile); echo "</pre>";die();
        $USER->Authorize($USER_ID, true);
        //LocalRedirect("/");
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
        <script>
        if( window.opener && window.opener.callAuthResult ) {
          window.opener.callAuthResult({
            errcode: '<?= $errcode ?>' // 0 - success
          });
        }
        window.close();
        window.location.replace('/');
        </script>
    </head>
    <body></body>
</html>
<?
//print_r($errcode);
die();
?>