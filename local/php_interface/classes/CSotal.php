<?
class CSotal
{
    private static $url = "http://discovery-ams.sotalcloud.com/cloud/ssoauth/cloud:saturntest/";
    private static $client_id = "cloud:saturntest_ottweb_device";
    private static $client_secret = 'gi2UQ_r3TBTAK0fjmFh6Oitc7y48tzy8ROxQm0WGfj8=';
    
    private $token;
	private static $lastError = null;
	private static $lastErrorCode = null;
    
    function __construct()
	{
		if($this->token == null)
		{
			$this->getToken();
		}
	}
    
    public static function register()
    {
        /*$response = self::sendRequest(
            array(),
			"register?client_id=$client_id&service_id=$service_id&timestamp=$t&signature=".urlencode($signature),
			'GET'
        );*/
        
        $response = self::sendRequest(
            array(
                "username" => "hawkart@rambler.ru",
                "password" => "123213",
                "title" => "123",
                "first_name" => "Артур",
                "last_name" => "Хейгетян",
                "postal_code" => "q3w",
                "country" => "21312",
                "city" => "12312",
                "address" => '123123'
            ),
			'register',
			'POST'
        );
    }
    
    private function getToken()
    {
        $t = time();
        $signature = md5('client_id'.self::$clientId.'service_id'.self::$serviceId.'timestamp'.$t.self::$secret);
        $client_id = self::$clientId;
        $service_id = self::$serviceId;

        $response = self::sendRequest(
            array(),
			"/token/service?client_id=$client_id&service_id=$service_id&timestamp=$t&signature=".urlencode($signature),
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
    
    protected function sendRequest($data, $method, $sendMethod = 'POST')
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::$url.$method);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        
        /*curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'X-Auth-Token: '.$this->token
        ));*/
        
		if($sendMethod=='POST')
		{
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);	
		}
        
		$response = curl_exec($ch);
        
		curl_close($ch);

		$response = json_decode($response, true);
        
        print_r($response);
        
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
        define("LOG_FILENAME", LOGS_DIR."MW".date("d_m_Y_H_i_s").".txt");
        
        if(!is_array($data) && !is_object($data))
		{
			AddMessage2Log($data);
		}
		else
		{
			AddMessage2Log(var_export($data,true));
		}
    }
    
    public static function GetLastError()
	{
		return self::$lastError;
	}

	public static function GetLastErrorCode()
	{
		return self::$lastErrorCode;
	}
}