<?
namespace Hawkart\Megatv;

use Bitrix\Main\Entity;
use Bitrix\Main\Localization;

Localization\Loc::loadMessages(__FILE__);

class CSotal
{
    private static $url = "http://discovery-ams.sotalcloud.com/";
    private static $client_id = "cloud:saturntest_ottweb_device";
    private static $client_secret = 'gi2UQ_r3TBTAK0fjmFh6Oitc7y48tzy8ROxQm0WGfj8=';
    private static $device_id = "saturn.tv";
    
    private static $token;
    private static $user_id;
    private static $subscriberToken;
    
    function __construct($USER_ID = false)
	{
		if($this->token == null)
		{
			$this->getDeviceToken();
		}
        
        if($this->user_id == null)
        {
            if($USER_ID)
            {
                $this->user_id = $USER_ID;
            }else{
                global $USER;
                $this->user_id = $USER->GetID();
            }
        }
	}
    
    private function getDeviceToken()
    {
        $t = time();
        $device_id = self::$device_id;
        $client_id = self::$client_id;
        $signature = md5('client_id'.self::$client_id.'timestamp'.$t.'device_id'.$device_id.self::$client_secret);
        
        $response = self::sendRequest(
            array(),
			"token/device?client_id=$client_id&timestamp=$t&device_id=$device_id&signature=".urlencode($signature),
			'GET'
        );
        
		if($response != false)
		{
			$this->token = $response["token"];
			return true;
		}
		else
		{
			$this->token = null;
			return false;
		}
    }
    
    public function register()
    {
        global $USER;
        $rsUser = \CUser::GetByID($this->user_id);
        $arUser = $rsUser->Fetch();
        
        if(empty($arUser["UF_SOTAL_LOGIN"]))
        {
            $arUser = \CUserEx::generateDataSotal();
        }else{
            return true;
        }
            
        $response = self::sendRequest(
            array(
                "username" => $arUser["UF_SOTAL_LOGIN"],
                "password" => $arUser["UF_SOTAL_PASS"],
                "title" => "",
                "first_name" => $arUser["NAME"],
                "last_name" => $arUser["LAST_NAME"],
                "postal_code" => $arUser["PERSONAL_ZIP"],
                "country" => $arUser["PERSONAL_COUNTRY"],
                "city" => $arUser["PERSONAL_CITY"],
                "address" => ""
            ),
			'cloud/ssoauth/cloud:saturntest/register',
			'POST'
        );
        
        if(intval($response["result"])==1)
        {
            return true;
        }else{
            
            $cUser = new \CUser;
            $cUser->Update($arUser["ID"], array(
                "UF_SOTAL_LOGIN" => "",
                "UF_SOTAL_PASS" => ""
            ));
            
            return false;
        }
    }
    
    public function getSsoKey()
    {
        global $USER;
        $rsUser = \CUser::GetByID($this->user_id);
        $arUser = $rsUser->Fetch();
        
        $response = self::sendRequest(
            array(
                "username" => $arUser["UF_SOTAL_LOGIN"],
                "password" => $arUser["UF_SOTAL_PASS"],
            ),
			'cloud/ssoauth/cloud:saturntest/1/subscriber/auth',
			'POST'
        );        
        
        if($response != false)
		{
			return $response["sso"];
		}
		else
		{
			return false;
		}
    }
    
    public function getSubscriberToken()
    {
        $token = urlencode($this->token);
        $sso_key = $this->getSsoKey();
        $response = self::sendRequest(
            array(),
			"token/subscriber_device/by_sso?auth_token=$token&sso_system=cloud.v2&sso_key=$sso_key",
			'GET'
        );
        
		if($response != false && intval($response["result"])==1)
		{
			$this->subscriberToken = $response["token"];
			return true;
		}
		else
		{
			$this->subscriberToken = null;
			return false;
		}
    }
    
    public function getEpgChannel($channel_epg_id)
    {
        $response = self::sendRequest(
            array(),
			"collection/epg.channel/query/dimension/extid/eq/".$channel_epg_id,
			'GET',
            true
        );
        
		if($response != false)
		{
			return $response;
		}
		else
		{
			return false;
		}
    }
    
    public function getLiveAsset($channel_obj_id)
    {
        $response = self::sendRequest(
            array(),
			"collection/vod.asset/query/dimension/epg_channel_id/eq/".$channel_obj_id,
			'GET',
            true
        );
        
		if($response != false)
		{
			return $response;
		}
		else
		{
			return false;
		}
    }
    
    public function getDevices()
    {
        $response = self::sendRequest(
            array(),
			"rdvr/devices/",
			'GET',
            true
        );
        
		if($response != false)
		{
			return $response;
		}
		else
		{
			return false;
		}
    }

    public function putRecord($arSchedule)
    {
        $start = strtotime($arSchedule["UF_DATE_START"]);
        $end = strtotime($arSchedule["UF_DATE_END"]);
        $duration = $end - $start;
        $channel_obj = $this->getEpgChannel($arSchedule["UF_CHANNEL_EPG_ID"]);
        $channel_asset = $this->getLiveAsset($channel_obj["collection"][0]["id"]);
        
        //получим список устройств, выполняющих запись
        $arDevices = $this->getDevices();
        
        if(empty($channel_asset["collection"][0]["er_lcn"]))
        {
            return false;
        }
        
        $response = self::sendRequest(
            array(
                "device_id" => $arDevices["devices"][0]["id"],
                "start_time" => $start,
                "duration" => $duration,
                "lcn" => $channel_asset["collection"][0]["er_lcn"],
            ),
			"rdvr/schedule/by_lcn",
			'POST',
            true
        );
        
        if($response != false && $response["result"])
		{
			return $response["recording_id"];
		}
		else
		{
			return false;
		}
    }
    
    public function cancelRecord($record_id)
    {
        $response = self::sendRequest(
            array(
                "recording_id" => $record_id
            ),
			"rdvr/cancel",
			'POST',
            true
        );
        if($response != false && $response["result"])
		{
			return $response;
		}
		else
		{
			return false;
		}
    }
    
    public function getScheduleList()
    {
        $response = self::sendRequest(
            array(),
			"rdvr/schedule",
			'GET',
            true
        );
        
		if($response != false)
		{
			return $response;
		}
		else
		{
			return false;
		}
    }
    
    public function getStreamUrl($recording_id)
    {
        $response = self::sendRequest(
            array(),
			"npvr/integration/content?recording_id=".$recording_id,
			'GET',
            true
        );
        
		if($response != false)
		{
			return $response;
		}
		else
		{
			return false;
		}
    }
    
    protected function sendRequest($data, $method, $sendMethod = 'POST', $addToken = false)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$url.$method);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $arHeader = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        
        if($addToken)
        {
            $arHeader[] = 'X-Auth-Token: '.$this->subscriberToken;
        }
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, $arHeader);
        
		if($sendMethod =='POST')
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data) );	
		}
        
		$response = curl_exec($ch);
		curl_close($ch);
		$response = json_decode($response, true);
        
        if($response["result"])
        {
            $log_file = "/logs/sotal/sotal_".date("d_m_Y_H").".txt";
        }else{
            $log_file = "/logs/sotal/error_".date("d_m_Y_H").".txt";
        }
        
        \CDev::log(array(
            "DATETIME" => date("d.m.Y H:i:s"),
            "METHOD"  => $method,
            "DATA"    => $data,
            "SEND_METHOD" => $sendMethod,
            "RESPONSE"  => $response,
            "LINE" => "--------------------------------------------------------------"
        ), false, $log_file);
        
		if($response["result"])
		{
			return $response;
		}
		else
		{	
			return false;
		}
	}
    
    private static function log($data)
    {
        define("LOG_FILENAME", "/logs/sotal_".date("d_m_Y_H_i_s").".txt");
        
        if(!is_array($data) && !is_object($data))
		{
			AddMessage2Log($data);
		}
		else
		{
			AddMessage2Log(var_export($data,true));
		}
    }
}