<?
class CUserEx
{
    function OnBeforeUserLogin($arFields)
    {
        $phone = preg_replace("/[^0-9]/", '', $arFields["LOGIN"]);
        
        if(\CDev::check_phone($phone))
        {
            $filter = Array("PERSONAL_PHONE" =>$phone);
        }else{
            $filter = Array("=EMAIL" =>$arFields["LOGIN"]);
        }
        
        $rsUsers = \CUser::GetList(($by="LAST_NAME"), ($order="asc"), $filter);
        if($user = $rsUsers->GetNext())
            $arFields["LOGIN"] = $user["LOGIN"];
    }
    
    function OnBeforeUserRegister($arFields)
    {
        $arFields["LOGIN"] = $arFields["EMAIL"];
        $arFields["PERSONAL_BIRTHDAY"] = $arFields["USER_PERSONAL_BIRTHDAY"];
    }
    
    function OnBeforeUserSendPasswordHandler($arFields)
    {
        /*$rsUser = CUser::GetByID($arFields["ID"]);
        $arUser = $rsUser->Fetch();
        
        $rsUser = CUser::GetByLogin($email);
        if($arUser["UF_PHONE_REG"]=="Y")
        {
            $text = "Проверочное слово: ".$arFields["USER_CHECKWORD"];
            CEchogroupSmsru::Send($arUser["PERSONAL_PHONE"], $text);
        }*/
    }
    
    function OnBeforeUserDeleteHandler($user_id)
    {
        \CModule::IncludeModule("iblock");
        \CModule::IncludeModule("sale");
        
        //Привязки к соц. сетям
        $arrFilter = array(
            "IBLOCK_ID" => USER_SOCIAL_IB,
            "PROPERTY_USER_ID" => $user_id,
        );
        $arSelect = array("ID");
        $rsRes = \CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
		while( $arItem = $rsRes->GetNext() )
        {
            \CIBlockElement::Delete($arItem["ID"]);
		}
        
        //Удаляем записи
        $result = \Hawkart\Megatv\RecordTable::getList(array(
            'filter' => array("UF_USER_ID"=>$user_id),
            'select' => array("ID"),
        ));
        while ($arRecord = $result->fetch())
        {
            \CRecordEx::delete($arRecord["ID"]);
        }
        
        //Удаляем счет
        if($arAccount = \CSaleUserAccount::GetByUserID($user_id, "RUR"))
        {
            \CSaleUserAccount::Delete($arAccount["ID"]);
        }
        
        //Удаляем заказы
        $arFilter = Array(
           "USER_ID" => $user_id,
        );
        $db_sales = \CSaleOrder::GetList(array("DATE_INSERT" => "ASC"), $arFilter);
        while ($ar_sales = $db_sales->Fetch())
        {
            \CSaleOrder::Delete($ar_sales["ID"]);
        }
        
        //Удаляем подписки
        $result = \Hawkart\Megatv\SubscribeTable::getList(array(
            'filter' => array("=UF_USER_ID" => $user_id),
            'select' => array("ID")
        ));
        if ($arSub = $result->fetch())
        {
            \Hawkart\Megatv\SubscribeTable::delete($arSub["ID"]);
        }

    }
    
    public static function generateDataSotal($USER_ID = false)
    {
        global $USER;
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
            
        $rsUser = \CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();

        $password = mb_substr(md5(uniqid(rand(),true)), 0, 12);
        
        $cUser = new \CUser;
        $cUser->Update($USER_ID, array(
            "UF_SOTAL_LOGIN" => "email_".$USER_ID."@"."megatv.ru",
            "UF_SOTAL_PASS" => $password
        ));
        
        $arUser["UF_SOTAL_LOGIN"] = "email_".$USER_ID."@"."megatv.ru";
        $arUser["UF_SOTAL_PASS"] = $password;
        
        return $arUser;
    }
    
    /**
     * При загрузке аватара уменьшаем его размер до 150х150px
     */
    public static function updateAvatar($USER_ID)
    {
        $imageMaxWidth = 216; // Максимальная ширина уменьшенной картинки 
        $imageMaxHeight = 216; // Максимальная высота уменьшенной картинки
        
        $rsUser = \CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();
        
        if(intval($arUser["PERSONAL_PHOTO"])>0)
        {
            $arFile = \CFile::GetFileArray($arUser["PERSONAL_PHOTO"]);
            
            // проверяем, что файл является картинкой
            if (!\CFile::IsImage($arFile["FILE_NAME"]))
            {
                echo "не является картинкой";
                return $arUser;
            }
                
            // Если размер больше допустимого
            if ($arFile["WIDTH"] > $imageMaxWidth || $arFile["HEIGHT"] > $imageMaxHeight)
            {
                // Временная картинка
                $tmpFilePath = $_SERVER['DOCUMENT_ROOT']."/upload/tmp/".$arFile["FILE_NAME"];
                
                // Уменьшаем картинку
                $resizeRez = \CFile::ResizeImageFile( // уменьшение картинки для превью
                    $source = $_SERVER['DOCUMENT_ROOT'].$arFile["SRC"],
                    $dest = $tmpFilePath,
                    array(
                        'width' => $imageMaxWidth,
                        'height' => $imageMaxHeight
                    ),
                    $resizeType = BX_RESIZE_IMAGE_EXACT,//BX_RESIZE_IMAGE_PROPORTIONAL, // метод ресайза
                    $waterMark = array(), // водяной знак (пустой)
                    $jpgQuality = 95 // качество уменьшенной картинки в процентах
                );
                
                // Записываем изменение в свойство
                if ($resizeRez && $tmpFilePath) 
                {
                    $arNewFile = \CFile::MakeFileArray($tmpFilePath);

                    $arNewFile['del'] = "Y";
                    $arNewFile['old_file'] = $arUser['PERSONAL_PHOTO'];
                    $arNewFile["MODULE_ID"] = "main";
                    $fields['PERSONAL_PHOTO'] = $arNewFile;
                    
                    $user = new \CUser;
                    $user->Update($arUser["ID"], $fields);
                    
                    $rsUser = \CUser::GetByID($USER_ID);
                    $arUser = $rsUser->Fetch();
                    
                    // Удалим временный файл
                    unlink($tmpFilePath);
                } 
            }
        }
        
        return $arUser;
    }
    
    //Добавляем подписку на бесплатные каналы по умолчанию
    public function OnAfterUserUpdateHandler(&$arFields)
    {
        if(intval($arFields["ID"])>0)
        {
            self::subcribeOnFreeChannels($arFields["ID"]);
            
            $rsUser = \CUser::GetByID($arFields["ID"]);
            $arUser = $rsUser->Fetch();
            if(empty($arUser["UF_SOTAL_LOGIN"]))
            {
                $arUser = self::generateDataSotal();
            }
        }    
    }
    
    function OnAfterUserRegisterHandler(&$arFields)
    {
        // если регистрация успешна то
        if($arFields["USER_ID"]>0)
        {
            self::subcribeOnFreeChannels($arFields["USER_ID"]);
            
            $rsUser = \CUser::GetByID($arFields["USER_ID"]);
            $arUser = $rsUser->Fetch();
            if(empty($arUser["UF_SOTAL_LOGIN"]))
            {
                $arUser = self::generateDataSotal();
            }
        }
        return $arFields;
    }
    
    public function subcribeOnFreeChannels($user_id = false)
    {
        global $USER;
        if(!$user_id && $USER->IsAuthorized())
            $user_id = $USER->GetID();
    
        if(intval($user_id)>0)
        {
            $result = \Hawkart\Megatv\ChannelBaseTable::getList(array(
                'filter' => array("UF_ACTIVE" => 1, "!UF_PRICE_H24" => true, "!UF_FORBID_REC"=>1),
                'select' => array("ID")
            ));
            while ($arChannel = $result->fetch())
            {
                $CSubscribe = new \Hawkart\Megatv\CSubscribe("CHANNEL");
                $CSubscribe->setUserSubscribe($arChannel["ID"], $user_id);
            }
        }
    }
    
    public static function getBudget($USER_ID=false)
    {
        return \CSaleAccountEx::budget($USER_ID);
    }
    
    public static function capacityAdd($USER_ID, $gb)
    {
        global $USER;
        if(!$USER_ID)
            $USER_ID = $USER->GetID();
            
        $rsUser = \CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();
        
        $capacity = $arUser["UF_CAPACITY"] + $gb;
        
        $cuser = new \CUser;
        $cuser->Update($arUser["ID"], array(
            "UF_CAPACITY" => $capacity
        ));
    }
    
    public static function getFriendsByProvider($provider_name = false, $user_id = false)
    {
        \CModule::IncludeModule("iblock");
        $config = $_SERVER['DOCUMENT_ROOT'].'/vendor/hybridauth/hybridauth/hybridauth/config.php';
        $arFriends = array();
        $arProviders = array();
        global $USER;
        
        if(!$user_id)
        {
            if($USER->IsAuthorized())
                $user_id = $USER->GetID();
        }
        
        if(!$user_id)
            return false;

        if(!$provider_name)
        {
            //Привязки к соц. сетям
            $arrFilter = array(
                "IBLOCK_ID" => USER_SOCIAL_IB,
                "PROPERTY_USER_ID" => $user_id,
            );
            $arSelect = array("ID", "PROPERTY_SOCIAL_PROVIDER");
            $rsRes = \CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
    		while( $arItem = $rsRes->GetNext() )
            {
                $arProviders[] = $arItem["PROPERTY_SOCIAL_PROVIDER_VALUE"];
    		}
        }
        
        try{
            $hybridauth = new \Hybrid_Auth( $config );
        	$adapter = $hybridauth->authenticate( $provider_name );
        	$user_contacts = $adapter->getUserContacts();
            
            print_r($user_contacts);
        }catch( Exception $e )
        {
            LocalRedirect("/?login=Y&social-error=".$e->getMessage());
            die();
        }
    } 
    
    public static function signup($request)
    {
        //CComponentUtil::__IncludeLang("/local/templates/megatv/components/bitrix/system.auth.registration/.default/ajax.php");
        CComponentUtil::__IncludeLang("/local/templates/megatv/components/bitrix/system.auth.registration/.default", "ajax.php", "ru");
        
        global $USER;
        if(!is_object($USER))
            $USER = new \CUser;
        
        $result = array();
        $result['status'] = 'error';
        $result['message'] = '';
        $result['errors'] = array();
        
        /*if (!check_bitrix_sessid()) 
        {
            $result['errors']["login"] = GetMessage('AUTH_ERROR_SESSION_EXPIRED');
        }*/
        
        if(!$USER->IsAuthorized() && count($result['errors'])==0)
        {
            //$EMAIL = htmlspecialcharsbx(strip_tags($request["login"]));
            $EMAIL = urldecode($request["login"]);
            $password = htmlspecialcharsbx($request["password"]);
            
            $phone = preg_replace("/[^0-9]/", '', $EMAIL);
            $phone[0] = "7";
        
            if(!\CDev::check_email($EMAIL) && !\CDev::check_phone($phone))
            {
                $result['errors']["login"] = GetMessage('AUTH_ERROR_DATA_FORMAT');
            }else{
                
                if(\CDev::check_phone($phone))
                {
                    $rsUsers = \CUser::GetList(($by="EMAIL"), ($order="desc"), Array("PERSONAL_PHONE" =>$phone));
                    if($arUser = $rsUsers->GetNext())
                    {
                        if($arUser["ACTIVE"]=="N")
                        {
                            return $result;
                        }
                        $result['errors']["login"] = GetMessage('AUTH_ERROR_PHONE_EXIST');
                    }
                }else{
                    $rsUsers = \CUser::GetList(($by="EMAIL"), ($order="desc"), Array("=EMAIL" =>$EMAIL));
                    if($arUser = $rsUsers->GetNext())
                    {
                        if($arUser["ACTIVE"]!="Y")
                        {
                            return $result;
                        }
                        $result['errors']["login"] = GetMessage('AUTH_ERROR_EMAIL_EXIST');
                    }
                }
            }
        
            /*if($AGREE!="on")
            {
                $result['errors']["agree"] = GetMessage('AUTH_ERROR_AGREE');
            }*/
            
            if(strlen($password)<6)
            {
                $result['errors']["password"] = GetMessage('AUTH_ERROR_PASSWORD');
            }
            
            if(count($result['errors'])==0)
            {
                global $USER;
                COption::SetOptionString("main","captcha_registration", "N");
                
                $default_group = COption::GetOptionString("main", "new_user_registration_def_group");
                if(!empty($default_group))
                    $arrGroups = explode(",", $default_group);
                
                $user = new CUser;
                $arFields = Array(
                	"LOGIN"             	=> $EMAIL,
                	"LID"               	=> SITE_ID,
                	"ACTIVE"            	=> "Y",//"N",
                	"PASSWORD"          	=> $password,
                	"CONFIRM_PASSWORD"  	=> $password,
                	"EMAIL"			        => $EMAIL,
                    "GROUP_ID"              => $arrGroups,
                    "CHECKWORD"             => md5(CMain::GetServerUniqID().uniqid()),
                    "CONFIRM_CODE"          => randString(8),
                    "USER_IP"               => $_SERVER["REMOTE_ADDR"],
                    "USER_HOST"             => @gethostbyaddr($_SERVER["REMOTE_ADDR"])
                );
                
                //Если ввели телефон
                if(\CDev::check_phone($phone))
                {
                    $arFields["PERSONAL_PHONE"] = $phone;
                    $arFields["EMAIL"] = $arFields["LOGIN"] = $phone."@megatv.su";
                    $arFields["UF_PHONE_REG"] = "Y";
                }
                
                $USER_ID = $user->Add($arFields);
                
        		if(intval($USER_ID)>0)
                {
                    self::subcribeOnFreeChannels($USER_ID);
                    
                    $arFields["USER_ID"] = $USER_ID;
                    $event = new \CEvent;
            		$event->SendImmediate("NEW_USER", SITE_ID, $arFields);
                    
                    if(\CDev::check_phone($phone))
                    {
                        $checkword = mb_substr(md5(uniqid(rand(),true)), 0, 8);
                        $cuser = new CUser;
                        $cuser->Update($USER_ID, array(
                            "UF_PHONE_CHECKWORD" => $checkword
                        ));
                        
                        $text = GetMessage('AUTH_ACTIVATE_CODE_TEXT').$checkword;
                        \CEchogroupSmsru::Send($phone, $text);
                        $result['message'] = "<font style='color:green'>".GetMessage('AUTH_REGISTER_SUCCESS_TEXT_1')."</font><br />";
                    }else{
                        
                        //$event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arFields);
                        $result['message'] = "<font style='color:green'>".GetMessage('AUTH_REGISTER_SUCCESS_TEXT_2')."</font><br />";
                    
                        self::capacityAdd($USER_ID, 1);   // за мэйл +1ГБ
                    }
                }
        
                $result['status'] = "success";
                
                //Бонус за регистрацию
                self::capacityAdd($USER_ID, BONUS_FOR_REGISTRATION);
        
                COption::SetOptionString("main", "captcha_registration", "Y");
            }else{
                $result['status'] = 'error';
                $result['message'] = $html;
            }
        }
        
        return $result;
    } 
    
    public static function login($request)
    {
        CComponentUtil::__IncludeLang("/local/templates/megatv/components/bitrix/system.auth.form/.default", "ajax.php", "ru");

        global $USER;
        if(!is_object($USER))
            $USER = new \CUser;
        
        $result = array();
        $result['status'] = 'error';
        $result['message'] = '';
        $result['errors'] = array();
        
        /*if (!check_bitrix_sessid()) 
        {
            $result['errors']["login"] = GetMessage('AUTH_ERROR_SESSION_EXPIRED');
        }*/
        
        if(count($result['errors'])==0)
        {
            $login = htmlspecialcharsbx($request["login"]);
            $password = htmlspecialcharsbx($request["password"]);
            $arAuthResult = $USER->Login($login, $password, "Y");
            
            if(!$USER->IsAuthorized())
            {
                $result['status'] = 'error';
                $result['message'] = $arAuthResult["MESSAGE"];
            }else{
                $result['status'] = 'success';
            }
        }
        
        return $result;
    }
    
    public static function logout($request)
    {
        global $USER;
        if(!is_object($USER))
            $USER = new \CUser;
        
        $result = array();
        $result['status'] = 'success';
        $result['message'] = '';
        $result['errors'] = array();
        
        $USER->Logout();
        
        return $result;
    }
}