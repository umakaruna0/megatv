<?
IncludeTemplateLangFile(__FILE__);
IncludeModuleLangFile(__FILE__);
Class CEchogroupSmsru 
{
    function OnBuildGlobalMenu(){

    }
    function TerminateEvent ($arFields,$arForm){
        $saved_e=unserialize(COption::GetOptionString("echogroup.smsru","EVENTS"));
        if(in_array($arForm["ID"],$saved_e)){
            global $USER;
            $uid=$USER->GetID();
            if($arFields["USER_ID"]) 
            $uid=$arFields["USER_ID"];
            if($arFields["UID"]) 
            $uid=$arFields["UID"];

            //if($uid>2) {
                $res=$USER->GetByID($uid);
                $arUser=$res->Fetch();
                if(!$arUser["PERSONAL_PHONE"]) 
                    $arUser["PERSONAL_PHONE"]=$arFields["TO"];
                    
                foreach($arFields as $k=>$v)
                    $arForm["MESSAGE"]=str_replace("#".$k."#",$v,$arForm["MESSAGE"]);
                    
                CEchogroupSmsru::Send($arUser["PERSONAL_PHONE"],$arForm["MESSAGE"]);
                
                return false;
            //}
        }
    }

    function Send($number,$message){
        if($number){
			if(!defined("BX_UTF"))$message=iconv("windows-1251","utf-8",$message);
			$res = new CHTTP;
			$token = $res->Get("http://sms.ru/auth/get_token");
			$arr=array(
				"login"		=>	COption::GetOptionString("echogroup.smsru","LOGIN"),
				"sha512"	=>	hash("sha512",COption::GetOptionString("echogroup.smsru","PASSWORD").$token.COption::GetOptionString("echogroup.smsru","API_KEY")),
				"token"		=>	$token,
				"to"		=>	$number,
				"text"		=>	$message
			);
			
			$html = $res->Post("http://sms.ru/sms/send", $arr);
			return $html;
        }
    }

    function CheckBalance(){
		$body = file_get_contents("http://sms.ru/my/balance?api_id=".COption::GetOptionString("echogroup.smsru","API_KEY"));
		$body = explode("\n", $body);
		$code = $body[0];
		$balance = $body[1];

		if ($code=="100")
			return $balance;
		else
			return self::CheckForErrors($code);
    }

    function CheckForErrors($msg){
        $arErr=array(
			"100"=>GetMessage("ECHOGROUP_SMSRU_ZAPROS_VYPOLNEN_NA"),
			"200"=>GetMessage("ECHOGROUP_SMSRU_NEPRAVILQNYY"),
			"210"=>GetMessage("ECHOGROUP_SMSRU_ISPOLQZUETSA_GD"),
			"211"=>GetMessage("ECHOGROUP_SMSRU_METOD_NE_NAYDEN"),
			"220"=>GetMessage("ECHOGROUP_SMSRU_SERVIS_VREMENNO_NEDO"),
			"300"=>GetMessage("ECHOGROUP_SMSRU_NEPRAVILQNYY1"),
			"301"=>GetMessage("ECHOGROUP_SMSRU_NEPRAVILQNYY_PAROLQ"),
			"302"=>GetMessage("ECHOGROUP_SMSRU_POLQZOVATELQ_AVTORIZ")
        );
        return $arErr[$msg];
    }

    function MultySend($arNumbers,$message){
        if(!empty($arNumbers)){
			if(!defined("BX_UTF"))$message=iconv("windows-1251","utf-8",$message);
			$res = new CHTTP;
			$token = $res->Get("http://sms.ru/auth/get_token");
			$arrep=array(" ",",","(",")","-");
			foreach($arNumbers as $k=>$v)
				$arNumbers[$k]=str_replace($arrep,"",$v);
			
			$arr=array(
				"login"		=>	COption::GetOptionString("echogroup.smsru","LOGIN"),
				"sha512"	=>	hash("sha512",COption::GetOptionString("echogroup.smsru","PASSWORD").$token.COption::GetOptionString("echogroup.smsru","API_KEY")),
				"token"		=>	$token,
				"to"		=>	implode($arNumbers,$number),
				"text"		=>	$message
			);
			
			$html = $res->Post("http://sms.ru/sms/send", $arr);
			return $html;
        }
    }
    
    function TerminateSubscribe ($arFields){
        $saved_s=unserialize(COption::GetOptionString("echogroup.smsru","SUBS"));
        if(in_array($arFields["ID"],$saved_s)){
            global $USER;
            $uid=$USER->GetID();
            if($arFields["USER_ID"]) 
            $uid=$arFields["USER_ID"];
            if($arFields["UID"]) 
            $uid=$arFields["UID"];
            
            if($uid>2) {
                $res=$USER->GetByID($uid);
                $arUser=$res->Fetch();
                if(!$arUser["PERSONAL_PHONE"]) 
                    $arUser["PERSONAL_PHONE"]=$arFields["TO"];
                    
                foreach($arFields as $k=>$v)
                    $arForm["MESSAGE"]=str_replace("#".$k."#",$v,$arForm["MESSAGE"]);
                    
                CEchogroupSmsru::Send($arUser["PERSONAL_PHONE"],$arForm["MESSAGE"]);
                
                return false;
            }
        }
    }
}
?>