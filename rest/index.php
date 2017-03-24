<?php
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../');
ini_set('max_execution_time', 30);
ini_set("session.cache_limiter", 0);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);
session_start();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require_once $DOCUMENT_ROOT.'/vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER = new \CUser;
  
$app = new Silex\Application();
$app['debug'] = false;
$app->register(new Silex\Provider\HttpCacheServiceProvider(), array(
    'http_cache.cache_dir' => __DIR__.'/cache/',
));

$app->before(function (Request $request) 
{
    //loging
    $rq = \Symfony\Component\HttpFoundation\Request::createFromGlobals();
    
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) 
    {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
    
    /*\CDev::log(array(
        'session' => $_SESSION,
        'cookie' => $rq->cookies->all(),
        'method' => $request->getPathInfo(),
        'request' => $request->request->all(),
        'files' => $_FILES,
        'post' => $_POST,
        'data' => $request->getContent(),
    ), false, "/rest/time_".date("Ymd").".txt");*/
});

$app->post('/login', function (Request $request) use ($app) 
{
    $post = array(
        'login' => $request->request->get('login'),
        'password'  => $request->request->get('pass'),
    );
    
    $result = \CUserEx::login($post);
    
    if($result["status"]=="error")
    {
        return $app->json($result, 400);
    }else{
        return $app->json($result, 200);
    }
});

$app->get('/logout', function (Request $request) use ($app) 
{
    $result = \CUserEx::logout();
    
    return $app->json($result, 200);
});

$app->post('/users', function (Request $request) use ($app) 
{
    $post = array(
        'login' => $request->request->get('login'),
        'password'  => $request->request->get('pass'),
        'agree' => $request->request->get('agree'),
        'g-recaptcha-response' => $request->request->get('g-recaptcha-response'),
    );
    
    $result = \CUserEx::signup($post);
    
    if($result["status"]=="error")
    {
        return $app->json($result, 400);
    }else{
        return $app->json($result, 200);
    }
});

$app->get('/users/social', function (Request $request) use ($app) 
{
    $arSocials = array(
        "yandex" => array(
            "icon" => "ya"
        ),
        "odnoklassniki" => array(
            "icon" => "ok"
        ),
        "google" => array(
            "icon" => "gp"
        ),
        "linkedin" => array(
            "icon" => "in"
        ),
        "vkontakte" => array(
            "icon" => "vk"
        ),
        "twitter" => array(
            "icon" => "tw"
        ),
        "instagram" => array(
            "icon" => "im"
        ),
        "facebook" => array(
            "icon" => "fb"
        )
    );
    
    foreach($arSocials as $key=>&$arSocial)
    {
        $arSocial["url"] = "https://tvguru.com/vendor/hybridauth/hybridauth/?provider=".$key."&env=dev";
    }
    return $app->json($arSocials, 200);
});

$app->get('/users/current', function (Request $request) use ($app) 
{
    global $USER;

    if($USER->IsAuthorized())
    {
        $rsUser = \CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        
        \CModule::IncludeModule("iblock");
        $arrFilter = array(
            "IBLOCK_ID" => PASSPORT_IB,
            "ACTIVE" => "Y",
            "PROPERTY_USER_ID" => $arUser["ID"]
        );
        $arSelect = array("ID", "PROPERTY_SERIA_NUMBER", "PROPERTY_WHEN_ISSUED", "PROPERTY_CODE_DIVISION", "PREVIEW_TEXT", "DETAIL_TEXT");
        $rsRes = \CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
    	$arPassport = $rsRes->GetNext();
        $arSeriaNumber = explode(" ", $arPassport["PROPERTY_SERIA_NUMBER_VALUE"]);
        
        return $app->json(array(
            "profile" => array(
                "name" => $arUser["NAME"],
                "last_name" => $arUser["LAST_NAME"],
                "second_name" => $arUser["SECOND_NAME"],
                "email" => $arUser["EMAIL"],
                "login" => $arUser["LOGIN"],
                "birthday" => $arUser["PERSONAL_BIRTHDAY"],
                "phone" => $arUser["PERSONAL_PHONE"],
            ),
            "passport" => array(
                "seria" => trim($arSeriaNumber[0]),
                "number" => trim($arSeriaNumber[1]),
                "who_issued" => $arPassport["PREVIEW_TEXT"],
                "when_issued" => $arPassport["PROPERTY_WHEN_ISSUED_VALUE"],
                "code_devision" => $arPassport["PROPERTY_CODE_DIVISION_VALUE"],
                "address" => $arPassport["DETAIL_TEXT"]
            ),
            "avatar" => $arUser['PERSONAL_PHOTO'] ? CFile::GetPath($arUser['PERSONAL_PHOTO']) : '',
            "busy" => $arUser["UF_CAPACITY_BUSY"],
            "capacity" => $arUser["UF_CAPACITY"],
            "budget" => floatval(\CUserEx::getBudget())
        ), 200);
    }else{
        return $app->json(["message" => "User is not authorized."], 401);
    }
});

$app->get('/users/current/social', function (Request $request) use ($app) 
{
    global $USER;

    if($USER->IsAuthorized())
    {
        $rsUser = \CUser::GetByID($USER->GetID());
        $arUser = $rsUser->Fetch();
        
        \CModule::IncludeModule("iblock");
        $arSocials = array();
        $arrFilter = array(
            "IBLOCK_ID" => USER_SOCIAL_IB,
            "ACTIVE" => "Y",
            "PROPERTY_USER_ID" => $USER->GetID()
        );
        $arSelect = array("PROPERTY_SOCIAL_PROVIDER");
        $rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
    	while( $arItem = $rsRes->GetNext() )
        {
            $arSocials[] = strtolower($arItem["PROPERTY_SOCIAL_PROVIDER_VALUE"]);
    	}
        
        $arrFilter = array(
            "IBLOCK_ID" => SOCIAL_CONFIG_IB,
            "ACTIVE" => "Y",
            "PROPERTY_PROJECT_VALUE" => $arSite["NAME"]
        );
        $arSelect = array("PROPERTY_PROVIDER", "PROPERTY_ICON", "PROPERTY_COUNT_GB");
        $rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, false, $arSelect );
        while( $arItem = $rsRes->GetNext() )
        {
            $provider = strtolower($arItem["PROPERTY_PROVIDER_VALUE"]);
            $arSocial = array(
                "icon" => $arItem["PROPERTY_ICON_VALUE"],
                "url" => "https://tvguru.com/vendor/hybridauth/hybridauth/?provider=".$provider."&env=dev",
                "connected" => false
            );
            if(in_array($provider, $arSocials))
            {
                $arSocial["connected"] = true;
            }
            
            $arResult["SOCIALS"][$provider] = $arSocial;            
        }
        
        return $app->json($arResult["SOCIALS"], 200);
    }else{
        return $app->json(["message" => "User is not authorized."], 401);
    }
});
        

$app->post('/users/current', function (Request $request) use ($app) 
{
    global $USER;

    if($USER->IsAuthorized())
    {
        $arPost = $request->request->all();
        foreach($arPost as &$value)
        {
            $value = htmlspecialcharsbx(trim($value));
        }
        $action = $arPost['action'];
    
        if($action == "passport")
        {
            $result = \CUserEx::setPassport($arPost);
        }
        else
        {
            $result = \CUserEx::setProfile($arPost);
        }
        
        if($result["status"]=="error")
        {
            return $app->json($result, 400);
        }else{
            return $app->json($result, 200);
        }

    }else{
        return $app->json(["message" => "User is not authorized."], 401);
    }
});

$app->post('/users/current/avatar', function (Request $request) use ($app) 
{
    global $USER;

    if($USER->IsAuthorized())
    {
        global $USER;
            
        $uid = $USER->GetID();
        $rsUser = CUser::GetByID($uid);
        $arUser = $rsUser->Fetch();
        
        $result["status"]="error";

        if(empty($_FILES["avatar"]["name"]) || $_FILES["avatar"]["type"]!="image/jpeg")
        {
            $result["status"]="error";
        }else{
            
            $arFile = array(
                "name" => $_FILES["avatar"]["name"],
                "tmp_name" => $_FILES["avatar"]["tmp_name"],
                "type" => $_FILES["avatar"]["type"],
                "size" => $_FILES["avatar"]["size"],
                "del" => "Y",
                "old_file" => $arUser['PERSONAL_PHOTO'],
                "MODULE_ID" => "main"
            );
            
            $cuser = new CUser;
            $cuser->Update($uid, array(
                "PERSONAL_PHOTO" => $arFile
            ));
            
            $result["status"]="success";
        }
        
        
        if($result["status"]=="error")
        {
            return $app->json($result, 400);
        }else{
            return $app->json($result, 200);
        }
    }else{
        return $app->json(["message" => "User is not authorized."], 401);
    }
});

$app->post('/users/current/pass', function (Request $request) use ($app) 
{
    global $USER;

    if($USER->IsAuthorized())
    {
        $post = array(
            'old_password' => $request->request->get('old_password'),
            'new_password'  => $request->request->get('new_password'),
            'new_password2' => $request->request->get('new_password2'),
        );
        $result = \CUserEx::changePassword($post);
        if($result["status"]=="error")
        {
            return $app->json($result, 400);
        }else{
            return $app->json($result, 200);
        }
    }else{
        return $app->json(["message" => "User is not authorized."], 401);
    }
});

$app->get('/users/current/subscription/channels', function (Request $request) use ($app) 
{
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $result = \Hawkart\Megatv\SubscribeTable::getList(array(
        'filter' => array("=UF_USER_ID" => $USER->GetID(), ">UF_CHANNEL_ID" => 0),
        'select' => array("UF_CHANNEL_ID", "ID", "UF_ACTIVE")
    ));
    while ($arSub = $result->fetch())
    {
        $selectedChannels[$arSub["UF_CHANNEL_ID"]] = $arSub;
    }
    
    $result = \Hawkart\Megatv\ChannelBaseTable::getList(array(
        'filter' => array("=UF_ACTIVE" => 1),
        'select' => array("ID", "UF_TITLE", "UF_ICON", "UF_PRICE_H24"),
        'order' => array("UF_PRICE_H24" => "DESC", "UF_TITLE"=>"ASC")
    ));
    while ($arChannel = $result->fetch())
    {
        $arSub = $selectedChannels[$arChannel["ID"]];
        if(intval($arSub["ID"])>0)
        {
            $arChannel["SELECTED"] = $arSub["UF_ACTIVE"]==1 ? true : false;
            $arChannel["SUBSCRIBE_ID"] = $arSub["ID"];
        }else{
            $arChannel["SELECTED"] = false;
            $arChannel["SUBSCRIBE_ID"] = false;
        }
        $arChannels[] = $arChannel;
    }
    
    return $app->json($arChannels, 200);
});

$app->post('/users/current/subscription/channels', function (Request $request) use ($app) 
{
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $channelID = $request->request->get('id');
    $select = $request->request->get('select');

    //get subcribe channel list
    $selectedChannels = array();
    $res = \Hawkart\Megatv\SubscribeTable::getList(array(
        'filter' => array("=UF_USER_ID" => $USER->GetID(), ">UF_CHANNEL_ID" => 0),
        'select' => array("UF_CHANNEL_ID", "ID")
    ));
    while ($arSub = $res->fetch())
    {
        $selectedChannels[$arSub["UF_CHANNEL_ID"]] = $arSub["ID"];
    }
    
    //check disable sub
    $res = \Hawkart\Megatv\ChannelBaseTable::getList(array(
        'filter' => array("=UF_FORBID_REC" => 1, "=ID" => $channelID),
        'select' => array("ID")
    ));
    if ($arChannel = $res->fetch())
    {
        return $app->json(array("status"=>"error", "message"=>"Нельзя подписаться на канал"), 400);
    }
    
    //update subsribes
    $result["status"] = "error";
    $CSubscribe = new \Hawkart\Megatv\CSubscribe("CHANNEL");
    if(!isset($selectedChannels[$channelID]))
    {
        $res = $CSubscribe->setUserSubscribe($channelID);
    }else{
        if($select)
        {
            $active = 1;
        }else{
            $active = 0;
        }
        
        $subscribeID = $selectedChannels[$channelID];
        $res = $CSubscribe->updateUserSubscribe($subscribeID, array("UF_ACTIVE"=>$active));
    }
     
    if($res)
    {
        $result["status"] = "success";  
    }
    
    if($result["status"]=="error")
    {
        return $app->json($result, 400);
    }else{
        return $app->json($result, 200);
    }
});

$app->put('/users/current/subscription/channels/{id}', function (Request $request, Silex\Application $app, $id)
{
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $select = $request->request->get('SELECTED');

    //get subcribe channel list
    $selectedChannels = array();
    $res = \Hawkart\Megatv\SubscribeTable::getList(array(
        'filter' => array("=UF_USER_ID" => $USER->GetID(), ">UF_CHANNEL_ID" => 0),
        'select' => array("UF_CHANNEL_ID", "ID")
    ));
    while ($arSub = $res->fetch())
    {
        $selectedChannels[$arSub["UF_CHANNEL_ID"]] = $arSub["ID"];
    }
    
    //check disable sub
    $res = \Hawkart\Megatv\ChannelBaseTable::getList(array(
        'filter' => array("=UF_FORBID_REC" => 1, "=ID" => $id),
        'select' => array("ID")
    ));
    if ($arChannel = $res->fetch())
    {
        return $app->json(array("status"=>"error", "message"=>"Нельзя подписаться на канал"), 400);
    }
    
    //update subsribes
    $result["status"] = "error";
    $CSubscribe = new \Hawkart\Megatv\CSubscribe("CHANNEL");
    if(!isset($selectedChannels[$id]))
    {
        $res = $CSubscribe->setUserSubscribe($id);
    }else{
        if(!empty($select))
        {
            $active = 1;
        }else{
            $active = 0;
        }
        
        $subscribeID = $selectedChannels[$id];
        $res = $CSubscribe->updateUserSubscribe($subscribeID, array("UF_ACTIVE" => $active));
    }
    
    //return $app->json(array("to_active" => $active, 'sub_id' => $subscribeID), 200); 
    
    if(empty($res["error"]))
    {
        $result["status"] = "success";  
    }else{
        $result["message"] = $res["error"];
    }
    
    if($result["status"]=="error")
    {
        return $app->json($result, 400);
    }else{
        return $app->json($result, 200);
    }
});


$app->get('/users/current/records/categories', function (Request $request) use ($app) 
{
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $arResult["CATEGORIES"] = array();
    $arStat = \Hawkart\Megatv\CStat::getByUser($USER->GetID());
    foreach($arStat["CATS"] as $category => $id)
    {
        $str = \CDev::translit($category, "ru", array("replace_space"=>"-", "replace_other"=>"-"));
        $arResult["CATEGORIES"][$category] = $str;
    }
    
    return $app->json($arResult["CATEGORIES"], 200);
});

$app->get('/users/current/records', function (Request $request) use ($app) 
{
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $arParams = $request->query->get('filter');
    
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    /**
     * Navigation
     */
    $page = 1;
    $countPerPage = 10;
    if(!empty($arParams["page"]))
    {
        $page = intval($arParams["page"]);
    }
    if(!empty($arParams["num"]))
    {
        $countPerPage = intval($arParams["num"]);
    }
    $offset = ($page-1)*$countPerPage;

    $arResult = \Hawkart\Megatv\RecordTable::getListByUser(array(
        "limit" => $countPerPage,
        "offset" => $offset,
        "category" => $arParams["category"]
    ));
    
    return $app->json($arResult, 200);
});

$app->post('/users/current/records', function (Request $request) use ($app) 
{  
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $prog_time = intval($request->request->get('cast_id'));
    if($USER->IsAuthorized() && $prog_time>0)
    {
        $selectedChannels = array();
        $result = \Hawkart\Megatv\SubscribeTable::getList(array(
            'filter' => array(
                "=UF_ACTIVE" => 1, 
                "=UF_USER_ID" => $USER->GetID(), 
                ">UF_CHANNEL_ID" => 0
            ),
            'select' => array("UF_CHANNEL_ID")
        ));
        while ($arSub = $result->fetch())
        {
            $selectedChannels[] = $arSub["UF_CHANNEL_ID"];
        }
    
        $USER_ID = $USER->GetID();
        $rsUser = \CUser::GetByID($USER_ID);
        $arUser = $rsUser->Fetch();
        
        //get inform about schedule
        $result = \Hawkart\Megatv\ScheduleTable::getList(array(
            'filter' => array("=ID" => $prog_time),
            'select' => array(
                "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_BASE_ID" => "UF_CHANNEL.UF_BASE_ID", "UF_PROG_ID",
                "UF_CHANNEL_EPG_ID" => "UF_CHANNEL.UF_BASE.UF_EPG_ID", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
                "UF_PROG_EPG_ID" => "UF_PROG.UF_EPG_ID", "UF_EPG_ID", "UF_CHANNEL_ID"
            ),
            'limit' => 1
        ));
        if ($arSchedule = $result->fetch())
        {
            $arSchedule["UF_DATE_START"] = $arSchedule['UF_DATE_START']->toString();
            $arSchedule["UF_DATE_END"] = $arSchedule['UF_DATE_END']->toString();
        }
        
        //check if schedule in recording yet. Deleted to recordable
        $update = false;
        $result = \Hawkart\Megatv\RecordTable::getList(array(
            'filter' => array(
                "=UF_USER_ID" => $USER_ID, 
                "=UF_SCHEDULE_ID" => $prog_time, 
            ),
            'select' => array("ID", "UF_DELETED"),
            'limit' => 1
        ));
        if ($arRecord = $result->fetch())
        {
            if(intval($arRecord["UF_DELETED"])==1)
            {
                $update = true;
                $update_id = $arRecord["ID"];
            }else{
                return $app->json(array(
                    "message"=> "Такая запись уже есть."
                ), 403);
            }
        }
        
        //money check
        $budget = \CUserEx::getBudget($user_id);
        if($budget<0)
        {
            return $app->json(array(
                "message" => "Для записи передачи пополните счет.",
                "state" => "budget"
            ), 403);
        }
        
        //Провеим, хватит ли пространства!
        $duration = strtotime($arSchedule["UF_DATE_END"])-strtotime($arSchedule["UF_DATE_START"]);
        $minutes = ceil($duration/60);
        $gb = $minutes*18.5/1024;
        $busy = floatval($arUser["UF_CAPACITY_BUSY"])+$gb;
        
        if($busy>=floatval($arUser["UF_CAPACITY"]))
        {
            return $app->json(array(
                "message" => "Не достаточно места на диске для записи",
                "state" => "require-space"
            ), 403);
        }else{
            
            if(in_array($arSchedule["UF_CHANNEL_BASE_ID"], $selectedChannels))
            {
                $log_file = "/logs/sotal/sotal_".date("d_m_Y_H").".txt";
                \CDev::log(array(
                    "ACTION"  => "PUT_TO_RECORD",
                    "DATA"    => array(
                        "SCHEDULE_ID"    => $prog_time,
                        "DATE"       => date("d.m.Y H:i:s")
                    )
                ), false, $log_file);
    
                if($update)
                {
                    \Hawkart\Megatv\RecordTable::update($update_id, array("UF_DELETED" => 0));
                }else{
                    \Hawkart\Megatv\RecordTable::create($arSchedule);
                }
                
                                
                //Inc rating for prog
                \Hawkart\Megatv\ProgTable::addByEpgRating($arSchedule["UF_PROG_EPG_ID"], 1);
                
                //change capacity for user 
                $cuser = new \CUser;
                $cuser->Update($arUser["ID"], array("UF_CAPACITY_BUSY"=>$busy));
                
                /**
                 * Данные в статистику
                 */                
                \Hawkart\Megatv\CStat::addByShedule($arSchedule["ID"], "record");
            }else{
                return $app->json(array(
                    "message" => "Вы не подписаны на этот канал",
                    "state" => "channel-not-subscribed"
                ), 403);
            }
        }    
    }
    
    //return $app->json(array("status" => "success"), 200);
    return $app->json([], 200);
    //header("Status: 200"); die();
});


/*$app->post('/users/current/records/{id}', function (Request $request) use ($app) 
{  
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $record_id = intval($request->request->get('id'));
    $progressInSeconds = intval($request->request->get('progressInSeconds'));
    $progressPosition = intval($request->request->get('progressPosition'));
    if($USER->IsAuthorized() && $record_id>0)
    {
        $arFields = array(
            "UF_PROGRESS_SECS" => $progressInSeconds,
            "UF_PROGRESS_PERS" => $progressPosition
        );
        
        if($progressPosition>75)
            $arFields["UF_WATCHED"] = 1;
        
        \Hawkart\Megatv\RecordTable::update($record_id, $arFields);    
    }
    
    return $app->json(array("status" => "success"), 200);
});*/

$app->get('/users/current/records/{id}', function (Silex\Application $app, $id)
{
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $arResult = \Hawkart\Megatv\RecordTable::getListByUser(array(
        "limit" => 1,
        "offset" => 0,
        "id" => $id
    ));
    
    if( count($arResult["items"])==0)
    {
        return $app->json(["message" => "Record does not exist."], 404);
    }else{
        return $app->json($arResult["items"][0], 200);
    }
});

$app->get('/users/current/records/{id}/similar', function (Silex\Application $app, Request $request, $id)
{
    global $USER;
    
    $arParams = $request->query->get('filter');
    
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    $arResult = \Hawkart\Megatv\RecordTable::getListByUser(array(
        "limit" => 1,
        "offset" => 0,
        "id" => $id
    ));
    
    $prog_id = $arResult["items"][0]["prog_id"];
    
    /**
     * Navigation
     */
    $page = 1;
    $countPerPage = 6;
    if(!empty($arParams["page"]))
    {
        $page = intval($arParams["page"]);
    }
    if(!empty($arParams["num"]))
    {
        $countPerPage = intval($arParams["num"]);
    }
    $offset = ($page-1)*$countPerPage;
    
    $arResult = \Hawkart\Megatv\ScheduleTable::getSimilarByProgId($prog_id, array(
        "limit" => $countPerPage,
        "offset" => $offset
    ));
    
    return $app->json($arResult, 200);
});

$app->delete('/users/current/records/{id}', function (Silex\Application $app, $id)
{
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
        
    $status = false;
    
    $record_id = intval($id);
    if($record_id>0)
    {
        $result = \Hawkart\Megatv\RecordTable::getById($record_id);
        $arRecord = $result->fetch();
        
        if($arRecord["UF_USER_ID"]==$USER->GetID())
        {
            $USER_ID = $USER->GetID();
            $rsUser = \CUser::GetByID($USER_ID);
            $arUser = $rsUser->Fetch();
    
            $arRecord["UF_DATE_START"] = $arRecord['UF_DATE_START']->toString();
            $arRecord["UF_DATE_END"] = $arRecord['UF_DATE_END']->toString();
            $duration = strtotime($arRecord["UF_DATE_END"])-strtotime($arRecord["UF_DATE_START"]);
            $minutes = ceil($duration/60);
            $gb = $minutes*(18.5/1024);
            
            $busy = floatval($arUser["UF_CAPACITY_BUSY"])-$gb; 
            $user = new \CUser;
            $user->Update($arUser["ID"], array("UF_CAPACITY_BUSY"=>$busy));
            
            \Hawkart\Megatv\RecordTable::update($record_id, array(
                "UF_DELETED" => 1
            ));
    
            return $app->json(array("status" => "success"), 200);
        }else{
            return $app->json(["message" => "Record does not exist."], 404);
        }
    }
});

$app->put('/users/current/records/{id}', function (Request $request, Silex\Application $app, $id)
{
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $arResult = \Hawkart\Megatv\RecordTable::getListByUser(array(
        "limit" => 1,
        "offset" => 0,
        "id" => $id
    ));
    
    if( count($arResult["items"])==0)
    {
        return $app->json(["message" => "Record does not exist."], 404);
    }else{
        
        $position = intval($request->request->get('position'));
        $arFields = array();
        
        if($position>0 && intval($arResult["items"][0]["duration"])>0)
        {
            $arFields["UF_PROGRESS_PERS"] = $position;
        
            if($position/$arResult["items"][0]["duration"]*100>75)
            {
                $arFields["UF_WATCHED"] = 1;
            }else{
                $arFields["UF_WATCHED"] = 0;
            } 
        }
        
        if(count($arFields)>0)
        {
            \Hawkart\Megatv\RecordTable::update($id, $arFields);
        }
        
        //return $app->json(array("status" => "success"), 200);
        return $app->json([], 200);
    }
});

$app->get('/users/current/transactions', function (Request $request) use ($app) 
{
    global $USER;
    if(!$USER->IsAuthorized())
    {
        return $app->json(["message" => "User is not authorized."], 401);
    }
    
    $arParams = $request->query->get('filter');
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    /**
     * Navigation
     */
    $page = 1;
    $countPerPage = 10;
    
    if(!empty($arParams["page"]))
    {
        $page = intval($arParams["page"]);
    }
    if(!empty($arParams["num"]))
    {
        $countPerPage = intval($arParams["num"]);
    }

    $arItems = \Hawkart\Megatv\CBilling::getTransaction(false, array(
        "page" => $page,
        "num" => $countPerPage
    ));
    
    return $app->json($arItems, 200);
});

$app->get('/channels', function (Request $request) use ($app) 
{
    global $USER;
    
    $arParams = $request->query->get('filter');
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    /**
     * User subscribe channel list.
     */
    $subChannels = array();
    $result = \Hawkart\Megatv\SubscribeTable::getList(array(
        'filter' => array("=UF_ACTIVE"=>1, "=UF_USER_ID" => $USER->GetID(), ">UF_CHANNEL_ID" => 0),
        'select' => array("UF_CHANNEL_ID")
    ));
    while ($arSub = $result->fetch())
    {
        $subChannels[] = $arSub["UF_CHANNEL_ID"];
    }
    
    /**
     * Get channels by city and exclude not subscribed
     */
    $arChannels = array();
    $arResult["ITEMS"] = \Hawkart\Megatv\ChannelTable::getActiveByCity();
    foreach($arResult["ITEMS"] as $arChannel)
    {
        if(!in_array($arChannel["UF_CHANNEL_BASE_ID"], $subChannels) && $USER->IsAuthorized())
                continue;
                
        $arChannels[$arChannel["UF_CHANNEL_BASE_ID"]] = $arChannel;
    }
    $arResult["ITEMS"] = $arChannels;
    
    /**
     * sort channels for user according statistics
     */
    if($USER->IsAuthorized())
    {
        $arItems = array();
        
        $arStatistic = \Hawkart\Megatv\CStat::getByUser();
        
        //sort channels by raiting
        uasort($arStatistic["CHANNELS"], function($a, $b){
            return strcmp($b, $a);
        });
    
        foreach($arStatistic["CHANNELS"] as $channel_id => $rating)
        {
            if(!empty($arResult["ITEMS"][$channel_id]))
            {
                $arItems[] = $arResult["ITEMS"][$channel_id];
                unset($arResult["ITEMS"][$channel_id]);
            }
        }
        
        if(count($arResult["ITEMS"])>0)
            $arItems = array_merge($arItems, $arResult["ITEMS"]);   
    
        $arResult["ITEMS"] = $arItems;
        unset($arItems);
    }
    
    /**
     * Navigation
     */
    $page = 1;
    $countPerPage = 10;
    
    if(!empty($arParams["page"]))
    {
        $page = intval($arParams["page"]);
    }
    if(!empty($arParams["num"]))
    {
        $countPerPage = intval($arParams["num"]);
    }
    
    $from = ($page-1)*$countPerPage;
    $to = $page*$countPerPage - 1;
    
    $arItems = array();
    $key = 0;
    foreach($arResult["ITEMS"] as $arChannel)
    {
        if($key>=$from && $key<=$to)
        {
            $arItems[] = $arChannel;
        }
        
        $key++;
    } 
    
    return $app->json(array(
        "items"=>$arItems, 
        "count" => count($arResult["ITEMS"]),
        "pageNum" => ceil(count($arResult["ITEMS"])/$countPerPage),
        "page" => $page,
        "num" => $countPerPage
    ), 200);
});

$app->get('/channels/{id}', function (Silex\Application $app, $id)
{
    global $USER;
    $arResult = array();
    
    $arFilter = array(
        "=UF_CHANNEL_ID" => intval($id),
    );
    $arSelect = array(
        'ID', 'UF_CHANNEL_ID', 'UF_CHANNEL_BASE_ID' => 'UF_CHANNEL.UF_BASE.ID', 
        'UF_TITLE' => 'UF_CHANNEL.UF_BASE.UF_TITLE', 'UF_ICON' => 'UF_CHANNEL.UF_BASE.UF_ICON',
        'UF_CODE' => 'UF_CHANNEL.UF_BASE.UF_CODE', "UF_IS_NEWS" => 'UF_CHANNEL.UF_BASE.UF_IS_NEWS',
        'UF_DESC' => 'UF_CHANNEL.UF_BASE.UF_DESC', 'UF_H1' => 'UF_CHANNEL.UF_BASE.UF_H1',
        'UF_DESCRIPTION' => 'UF_CHANNEL.UF_BASE.UF_DESCRIPTION', 
        'UF_KEYWORDS' => 'UF_CHANNEL.UF_BASE.UF_KEYWORDS',
        'UF_CHANNEL_BASE_ACTIVE' => 'UF_CHANNEL.UF_BASE.UF_ACTIVE'
    );
    $obCache = new \CPHPCache;
    if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/channel-detail/"))
    {
    	$arResult = $obCache->GetVars();
    }
    elseif($obCache->StartDataCache())
    {
        $arResult = array();
        $result = \Hawkart\Megatv\ChannelCityTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'limit' => 1,
        ));
        if ($arResult = $result->fetch())
        {
            $arResult["ID"] = $arResult["UF_CHANNEL_ID"];
            $arResult["DETAIL_PAGE_URL"] = "/channels/".$arResult['UF_CODE']."/";
            $title = $arResult["UF_TITLE"]." -  телепрограмма на сегодня, программа телепередач канала ".$arResult["UF_H1"]." на ".$arSite["NAME"];
            if($arResult["UF_H1"]=="5 канал")
                $title = str_replace("канала ", "", $title);
            
            $title = str_replace("TvGuru", $arSite["NAME"], $title);
            
            $arResult["PAGE_TITLE"] = $title;
        }
        $obCache->EndDataCache($arResult); 
    }
    
    if($USER->IsAuthorized())
    { 
        $channel_ids = \Hawkart\Megatv\ChannelTable::getActiveIdByCityByUser();
    }else{
        $channel_ids = \Hawkart\Megatv\ChannelTable::getActiveIdByCity();
    }
    
    /*$arChannels = \Hawkart\Megatv\ChannelTable::getActiveByCity();
    $exist = false;
    foreach($arChannels as $arChannel)
    {
        if($arChannel['ID']==$arResult["ID"]) 
            $exist = true;
            
        break;
    }
    
    if(!$exist && intval($arResult["ID"])==0)
        return $app->json(["message" => "Channel does not exist."], 404);*/
    
    if(!in_array($arResult["ID"], $channel_ids))
        return $app->json(["message" => "Channel does not exist."], 404);
    
    /**
     * User subscribe channel list.
     */
    $subChannels = array();
    $result = \Hawkart\Megatv\SubscribeTable::getList(array(
        'filter' => array("=UF_ACTIVE"=>1, "=UF_USER_ID" => $USER->GetID(), ">UF_CHANNEL_ID" => 0),
        'select' => array("UF_CHANNEL_ID")
    ));
    while ($arSub = $result->fetch())
    {
        $subChannels[] = $arSub["UF_CHANNEL_ID"];
    }
    
    if( (!in_array($arResult["UF_CHANNEL_BASE_ID"], $subChannels) && $USER->IsAuthorized())
        || empty($arResult) || intval($arResult["UF_CHANNEL_BASE_ACTIVE"])!=1)
    {
        return $app->json(["message" => "Channel does not exist."], 404);
    }else{
        return json_encode($arResult);
    }
});

$app->get('/channels/{id}/casts', function (Request $request, Silex\Application $app, $id)
{
    global $USER;
    
    $arParams = $request->query->get('filter');
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    $arDatetime = \CTimeEx::getDatetime();
    $date_now = $arDatetime["SERVER_DATETIME_WITH_OFFSET"];
    
    /**
     * Get records statuses by user
     */
    $arRecordsStatuses = \Hawkart\Megatv\RecordTable::getListStatusesByUser();
    
    $curDate = $date = date("d.m.Y H:i:s");
    if(!empty($arParams["date"]))
    {
        $date = $arParams["date"];
    }
    
    $currentDateTime = date("Y-m-d H:i:s", strtotime($date));

    $arResult = array();
    $arResult["date"] = substr($currentDateTime, 0, 10);
    $arScheduleList = \Hawkart\Megatv\ScheduleCell::getByChannelAndTime(intval($id), $currentDateTime);
    
    if($USER->IsAuthorized())
    { 
        $channel_ids = \Hawkart\Megatv\ChannelTable::getActiveIdByCityByUser();
    }else{
        $channel_ids = \Hawkart\Megatv\ChannelTable::getActiveIdByCity();
    }
    if(!in_array($id, $channel_ids))
        return $app->json(["message" => "Channel does not exist."], 404);
    
    
    if(count($arScheduleList)==0)
        return $app->json(["message" => "No generated cell for channel."], 404);
        
    $is_half = 0;
    $arHalf = array();
    
    foreach($arScheduleList as &$arProg)
    {
        $time = substr($arProg['UF_DATE_START'], 11, 5);       
        $arStatus = \Hawkart\Megatv\CScheduleTemplate::status($arProg, $arRecordsStatuses);
        $status = $arStatus["status"];
        
        if(intval($arProg["UF_BASE_FORBID_REC"])==1 && $USER->IsAuthorized() || !\CTimeEx::dateDiff($date_now, $arProg["DATE_END"]))
        {
            $status = "";
        }
        
        if($curDate!=$date)
        {
            $arProg["TIME_POINTER"] = false;
        }
        
        if($arProg["IS_ADV"])
        {
            $img_path = $arProg["PICTURE"];
        }else{
            if($arProg["CLASS"]=="one")
            {
                $img_path = \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(288, 288));
            }else if($arProg["CLASS"]=="double"){
                $img_path = \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(576, 288));
            }else{
                $img_path = \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(288, 144));
            }
            
            if($status=="viewed")
            {
                $img_path = SITE_TEMPLATE_PATH."/ajax/img_grey.php?&path=".urlencode($_SERVER["DOCUMENT_ROOT"].$img_path);
            }
        }
        
        $_arRecord = array(
            "id" => $arProg["ID"],
            "channel_id" => $arProg["UF_CHANNEL_ID"],
            "time" => $time,
        	"date" => substr($arProg["DATE_START"], 0, 10),//$date,
            "date_start" => $arProg["DATE_START"],
            "date_end" => $arProg["DATE_END"],
        	"link" => $arProg["DETAIL_PAGE_URL"],
        	"name" => \Hawkart\Megatv\CScheduleTemplate::cutName(\Hawkart\Megatv\ProgTable::getName($arProg), 35),
        	"on_air" => $arProg["TIME_POINTER"],
            "image" => $img_path,
            "status" => "status-".$status,
            "rating" => $arProg["UF_RATING"],
            "is_clone" => $arProg["CLONE"],
            "is_adv" => $arProg["IS_ADV"],
            "prog_id" => $arProg["UF_PROG_ID"]             
        );
        
        if($status=="recording")
        {
            $_arRecord["record_id"] = $arRecordsStatuses["RECORDING"][$arProg["ID"]]["ID"];
        }else if($status=="recorded")
        {
            $_arRecord["record_id"] = $arRecordsStatuses["RECORDED"][$arProg["ID"]]["ID"];
        }else{
            $_arRecord["record_id"] = "";
        }
        
        if($arProg["CLASS"]=="half")
        {
            $is_half++;
            
            $arHalf[] = $_arRecord;
            
            if($is_half==2)
            {
                $arResult["items"][] = array($arProg["CLASS"] => $arHalf);
                $is_half = 0;
                $arHalf = array();
            }
        }else{
            $arResult["items"][] = array($arProg["CLASS"] => $_arRecord);
        }
    }
    
    
    //$arGeo = \Hawkart\Megatv\CityTable::getGeoCity();
    //$city_id = $arGeo["ID"];
    //$arResult["city_id"] = $city_id;
    
    return new Response(
        json_encode($arResult),
        200,
        ['Content-Type' => 'application/json', 'Cache-Control' => 's-maxage=3600, public']
    );
    //return $app->json($arResult, 200);
});

$app->get('/channels/{id}/casts_test', function (Request $request, Silex\Application $app, $id)
{
    global $USER;
    
    $arParams = $request->query->get('filter');
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    $arRecordsStatuses = \Hawkart\Megatv\RecordTable::getListStatusesByUser();
    
    $curDate = $date = date("d.m.Y H:i:s");
    if(!empty($arParams["date"]))
    {
        $date = $arParams["date"];
    }
    
    $currentDateTime = date("Y-m-d H:i:s", strtotime($date));

    $arResult = array();
    $arResult["date"] = substr($currentDateTime, 0, 10);
    $arScheduleList = \Hawkart\Megatv\ScheduleCell::getByChannelAndTime(intval($id), $currentDateTime);
    
    /*if($USER->IsAuthorized())
    { 
        $channel_ids = \Hawkart\Megatv\ChannelTable::getActiveIdByCityByUser();
    }else{
        $channel_ids = \Hawkart\Megatv\ChannelTable::getActiveIdByCity();
    }
    if(!in_array($id, $channel_ids))
        return $app->json(["message" => "Channel does not exist."], 404);
    
    
    if(count($arScheduleList)==0)
        return $app->json(["message" => "No generated cell for channel."], 404);*/
        
    return new Response(
        json_encode($arScheduleList),
        200,
        ['Content-Type' => 'application/json', 'Cache-Control' => 's-maxage=3600, public']
    );
});

$app->get('/casts/{id}/similar', function (Silex\Application $app, Request $request, $id)
{
    global $USER;
    
    $arParams = $request->query->get('filter');
    
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    /**
     * Navigation
     */
    $page = 1;
    $countPerPage = 6;
    if(!empty($arParams["page"]))
    {
        $page = intval($arParams["page"]);
    }
    if(!empty($arParams["num"]))
    {
        $countPerPage = intval($arParams["num"]);
    }
    $offset = ($page-1)*$countPerPage;
    
    $arResult = \Hawkart\Megatv\ScheduleTable::getSimilar($id, array(
        "limit" => $countPerPage,
        "offset" => $offset
    ));
    
    return $app->json($arResult, 200);
});

$app->get('/casts/search', function (Silex\Application $app, Request $request)
{
    global $USER;
    $arResult = array();
    
    //Query
    $query = $request->query->get('query');
    $query = htmlspecialcharsbx(urldecode($query));
    if(strlen($query)==0)
        return $app->json(["message" => "Empty query string."], 404);
    
    /**
     * Navigation
     */
    $arParams = $request->query->get('filter');
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    $page = 1;
    $countPerPage = 6;
    if(!empty($arParams["page"]))
    {
        $page = intval($arParams["page"]);
    }
    if(!empty($arParams["num"]))
    {
        $countPerPage = intval($arParams["num"]);
    }
    $offset = ($page-1)*$countPerPage;
    
    $dateStart = date("Y-m-d H:00:00");
    $arFilter = array(
        "=UF_PROG.UF_ACTIVE" => 1,
        ">=UF_DATE_START" => new \Bitrix\Main\Type\DateTime($dateStart, 'Y-m-d H:i:s'),
        '%UF_PROG.UF_TITLE' => strtolower($query)
    );
    if($USER->IsAuthorized())
    { 
        $arFilter["=UF_CHANNEL_ID"] = \Hawkart\Megatv\ChannelTable::getActiveIdByCityByUser();
    }else{
        $arFilter["=UF_CHANNEL_ID"] = \Hawkart\Megatv\ChannelTable::getActiveIdByCity();
    }
    $arSelect = array(
        "ID", "UF_CODE", "UF_DATE_START", "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_PROG_ID",
        "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE", "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
        "UF_CHANNEL_CODE" => "UF_CHANNEL.UF_BASE.UF_CODE", "UF_ID" => "UF_PROG.UF_EPG_ID",
        "UF_PROG_CODE" => "UF_PROG.UF_CODE"
    );
    
    $obCache = new \CPHPCache;
    if( $obCache->InitCache(3600*3, serialize($arFilter).serialize($arSelect).$countPerPage."-".$offset, "/search-ajax/"))
    {
    	$arResult = $obCache->GetVars();
    }
    elseif($obCache->StartDataCache())
    {
        $arExclude = array();
        $result = \Hawkart\Megatv\ScheduleTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'limit' => $countPerPage,
            'offset' => $offset,
            'order' => array("UF_PROG.UF_RATING" => "DESC"),
        ));
        while ($arSchedule = $result->fetch())
        {
            /*if(in_array($arSchedule["UF_ID"], $arExclude))
            {
                continue;
            }else{
                $arExclude[] = $arSchedule["UF_ID"];
            }*/
            
            $arSchedule["UF_DATE_START"] = $arSchedule["DATE_START"] = $arSchedule['UF_DATE_START']->toString();
            
            $arJson = array();
            $arJson["date"] = substr($arSchedule["UF_DATE_START"], 11, 5)." | ".substr($arSchedule["UF_DATE_START"], 0, 10);
            $arJson["title"] = $arSchedule["UF_TITLE"];
            if($arSchedule["UF_IMG_PATH"])
            {
                $src = \Hawkart\Megatv\CFile::getCropedPath($arSchedule["UF_IMG_PATH"], array(300, 300));
                //$renderImage = CFile::ResizeImageGet($src, Array("width"=>60, "height"=>60));
                $arJson["thumbnail"] = $src;
            }
            else
            {
                $arJson["thumbnail"] = "null";
            }
                
            $arJson["id"] = $arSchedule["ID"];
            //$arJson["tokens"] = array();
            $arJson["prog_id"] = $arSchedule["UF_PROG_ID"];
            $arJson["link"] = "/channels/".$arSchedule["UF_CHANNEL_CODE"]."/".$arSchedule["UF_PROG_CODE"]."/?event=".$arSchedule["ID"];
            $arResult["items"][] = $arJson;
        }
        
        $maxRecord = \Hawkart\Megatv\ScheduleTable::getList([
           'select' => [new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)')],
           'filter' => $arFilter,
        ])->fetch()['CNT'];
        
        $arResult["pageNum"] = ceil($maxRecord/$countPerPage);
        
        $obCache->EndDataCache($arResult);
    }
    
    return $app->json($arResult, 200);
});

$app->get('/casts/{id}', function (Silex\Application $app, $id)
{
    $arFilter = array(
        "=ID" => $id
    );
    $arSelect = array(
        "ID", "UF_DATE_START", "UF_DATE_END", "UF_DATE", "UF_CHANNEL_ID", "UF_ICON" => "UF_CHANNEL.UF_BASE.UF_ICON", 
        "UF_PROG_ID"
    );
    $obCache = new \CPHPCache;
    if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/shedule-detail/"))
    {
    	$arResult = $obCache->GetVars();
    }
    elseif($obCache->StartDataCache())
    {
        //get channel by code
        $result = \Hawkart\Megatv\ScheduleTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'limit' => 1
        ));
        if ($arShedule = $result->fetch())
        {
            $arResult["ID"] = $arShedule["ID"];
            $arResult["UF_ICON"] = $arShedule["UF_ICON"];
            $arResult["UF_CHANNEL_ID"] = $arShedule["UF_CHANNEL_ID"];
            $arResult["UF_DATE_START"] = $arResult["DATE_START"] = \CTimeEx::dateOffset($arShedule['UF_DATE_START']->toString());
            $arResult["UF_DATE_END"] = $arResult["DATE_END"] = \CTimeEx::dateOffset($arShedule['UF_DATE_END']->toString());
            $arResult["UF_DATE"] = $arResult["DATE"] = substr($arShedule["DATE_START"], 0, 10);
            $sec = strtotime($arResult["DATE_END"]) - strtotime($arResult["DATE_START"]);
            $arResult["DURATION"] = \CTimeEx::secToStr($sec);
            $arResult["UF_PROG_ID"] = $arShedule["UF_PROG_ID"];
        }
        $obCache->EndDataCache($arResult); 
    }
    
    if(intval($arResult["UF_PROG_ID"])==0)
        return $app->json(array("status" => "error", "message" => "not active cast"), 400);
    
    $arFilter = array("=ID" => $arResult["UF_PROG_ID"]);
    $arSelect = array(
        "ID", "UF_TITLE", "UF_SUB_TITLE", "UF_IMG_PATH" => "UF_IMG.UF_PATH",
        "UF_RATING", "UF_DESC", "UF_SUB_DESC", "UF_GANRE", "UF_YEAR_LIMIT", "UF_COUNTRY",
        "UF_YEAR", "UF_DIRECTOR", "UF_PRESENTER", "UF_ACTOR", "UF_CATEGORY"
    );
    $obCache = new \CPHPCache;
    if( $obCache->InitCache(86400, serialize($arFilter).serialize($arSelect), "/prog-detail/"))
    {
    	$arProg = $obCache->GetVars();
    }
    elseif($obCache->StartDataCache())
    {
        $result = \Hawkart\Megatv\ProgTable::getList(array(
            'filter' => $arFilter,
            'select' => $arSelect,
            'limit' => 1
        ));
        if ($arProg = $result->fetch())
        {
            $arProg["UF_PROG_ID"] = $arProg["ID"];
            $arProg["PICTURE"]["SRC"] = \Hawkart\Megatv\CFile::getCropedPath($arProg["UF_IMG_PATH"], array(600, 600));
            $arProg["KEYWORDS"] = array($arProg["UF_CATEGORY"], $arProg["UF_GANRE"]);
        }
        unset($arProg["ID"]);
        $obCache->EndDataCache($arProg);  
    }
    
    if(!empty($arProg))
        $arResult = array_merge($arResult, $arProg);
    
    foreach(array("UF_DIRECTOR", "UF_PRESENTER", "UF_ACTOR") as $type)
    {
        $_arResult[$type] = array();
        $arPeoples = explode(",", $arResult[$type]);
    
        foreach($arPeoples as $actor)
        {
            $actor = trim($actor);
            if(!empty($actor))
            {
                $link = \Hawkart\Megatv\PeopleTable::getKinopoiskLinkByName($actor);
                $link = str_replace("//name", "/name", $link);
                if(empty($link)) $link = "#";
                $_arResult[$type][] = array(
                    "NAME" => $actor,
                    "LINK" => $link
                );
            }
        }
        $arResult[$type] = $_arResult[$type];
        unset($_arResult[$type]);
    }
    
    /**
     * Get records statuses by user
     */
    $arRecordsStatuses = \Hawkart\Megatv\RecordTable::getListStatusesByUser();
    
    $arStatus = \Hawkart\Megatv\CScheduleTemplate::status(array(
        "ID" => $arResult["ID"],
        "UF_CHANNEL_ID" => $arResult["UF_CHANNEL_ID"],
        "DATE_START" => $arResult["DATE_START"],
        "DATE_END" => $arResult["DATE_END"]
    ), $arRecordsStatuses);
    
    $arResult["status"] = "status-".$arStatus["status"];
    if($arStatus["status"]=="recording")
    {
        $arResult["record_id"] = $arRecordsStatuses["RECORDING"][$arResult["ID"]]["ID"];
    }else if($arStatus["status"]=="recorded")
    {
        $arResult["record_id"] = $arRecordsStatuses["RECORDED"][$arResult["ID"]]["ID"];
    }else{
        $arResult["record_id"] = "";
    }
    $arResult["prog_id"] = $arResult["UF_PROG_ID"];
    
    return new Response(
        json_encode($arResult),
        200,
        ['Content-Type' => 'application/json', 'Cache-Control' => 's-maxage=36000, public']
    );
    
    //return json_encode($arResult);
});

$app->get('/progs/{id}', function (Silex\Application $app, $id)
{
    global $USER;
    
    $arResult = \Hawkart\Megatv\ProgTable::detailForRest($id);
    
    return json_encode($arResult);
});

$app->get('/progs/{id}/similar', function (Silex\Application $app, Request $request, $id)
{
    global $USER;
    
    $arParams = $request->query->get('filter');
    
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    /**
     * Navigation
     */
    $page = 1;
    $countPerPage = 6;
    if(!empty($arParams["page"]))
    {
        $page = intval($arParams["page"]);
    }
    if(!empty($arParams["num"]))
    {
        $countPerPage = intval($arParams["num"]);
    }
    $offset = ($page-1)*$countPerPage;
    
    $arResult = \Hawkart\Megatv\ScheduleTable::getSimilarByProgId($id, array(
        "limit" => $countPerPage,
        "offset" => $offset
    ));
    
    return $app->json($arResult, 200);
});

$app->get('/recommended', function (Request $request) use ($app) 
{
    global $USER;
    
    $arParams = $request->query->get('filter');
    
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    /**
     * Navigation
     */
    $page = 1;
    $countPerPage = 10;
    if(!empty($arParams["page"]))
    {
        $page = intval($arParams["page"]);
    }
    if(!empty($arParams["num"]))
    {
        $countPerPage = intval($arParams["num"]);
    }
    $offset = ($page-1)*$countPerPage;
    
    /**
     * Date
     */
    $date = date("d.m.Y H:i:s");
    if(!empty($arParams["date"]))
    {
        $date = $arParams["date"];
    }
    $date = date("Y-m-d H:i:s", strtotime($date));
    
    $arResult = \Hawkart\Megatv\ScheduleTable::getRecommend(array(
        "limit" => $countPerPage,
        "offset" => $offset,
        "date" => $date,
        "category" => $arParams["category"]
    ));
    
    return $app->json($arResult, 200);
});

$app->get('/recommended/categories', function (Request $request) use ($app) 
{
    global $USER;
    
    $arResult["CATEGORIES"] = array();
    if($USER->IsAuthorized())
    {
        $arStat = \Hawkart\Megatv\CStat::getByUser($USER->GetID());
        foreach($arStat["CATS"] as $category => $id)
        {
            $str = \CDev::translit($category, "ru", array("replace_space"=>"-", "replace_other"=>"-"));
            $arResult["CATEGORIES"][$category] = $str; 
        }
    }else{
        
        $arCats = \Hawkart\Megatv\ProgTable::getCategoryAll();
        
        foreach($arCats as $key=>$category)
        {
            $str = \CDev::translit($category, "ru", array("replace_space"=>"-", "replace_other"=>"-"));
            $arResult["CATEGORIES"][$category] = $str;
        }
    }
    
    return $app->json($arResult["CATEGORIES"], 200);
});

$app->get('/langs', function (Request $request) use ($app) 
{    
    $arLangs = \Hawkart\Megatv\CountryTable::getLangList();
    return $app->json($arLangs, 200);
});

$app->post('/langs', function (Request $request) use ($app) 
{  
    $lang_id = intval($request->request->get('lang_id'));
    \Hawkart\Megatv\CountryTable::setCountry($lang_id);
    return $app->json(["status" => "success"], 200);
});

$app->get('/langs/current', function (Request $request) use ($app) 
{
    $arLangs = \Hawkart\Megatv\CountryTable::getLangList();
    foreach($arLangs as $arLang)
    {
        if($arLang["current"])
            return $app->json($arLang, 200);
    }
    return $app->json(["message" => "No current lang."], 404);
});

$app->get('/langs/{id}', function (Silex\Application $app, $id)
{
    $arLangs = \Hawkart\Megatv\CountryTable::getLangList();
    foreach($arLangs as $arLang)
    {
        if($arLang["id"]==$id)
            return $app->json($arLang, 200);
    }
    return $app->json(["message" => "No lang with such id."], 404);
});

$app->get('/cities', function (Request $request) use ($app) 
{
    $arParams = $request->query->get('filter');
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    $arCities = \Hawkart\Megatv\CityTable::getLangCityList($arParams["lang_id"]);

    return $app->json($arCities, 200);
});

$app->post('/cities', function (Request $request) use ($app) 
{  
    $city_id = intval($request->request->get('city_id'));
    \Hawkart\Megatv\CityTable::setGeoCity($city_id);
    \Hawkart\Megatv\GuestTable::setCity($city_id);
    return $app->json(["status" => "success"], 200);
});

$app->get('/cities/current', function (Request $request) use ($app) 
{
    $arGeo = \Hawkart\Megatv\CityTable::getGeoCity();
    $arResult = \Hawkart\Megatv\CityTable::convertForRest($arGeo);
    unset($arGeo);
    return $app->json($arResult, 200);
});

$app->get('/cities/{id}', function (Silex\Application $app, $id)
{
    $arCities = \Hawkart\Megatv\CityTable::getLangCityList();
    foreach($arCities as $arCity)
    {
        if($arCity["id"]==$id)
            return $app->json($arCity, 200);
    }
    return $app->json(["message" => "No city with such id."], 404);
});

$app->get('/timetable/state', function (Request $request) use ($app)
{
    $arParams = $request->query->get('filter');
    if(!empty($arParams) && !is_array($arParams))
    {
        $arParams = json_decode($arParams, true);
    }
    
    $cell_id = $arParams["id"];
    session_start();
    $guest_id = $_SESSION["SESS_IP"];
    session_write_close();
    
    $arDatetime = \CTimeEx::getDatetime();
    $currentDateTime = date("Y-m-d H:i:s", strtotime($arDatetime["SERVER_DATETIME_WITH_OFFSET"]));
    $current_cell_id = \Hawkart\Megatv\ScheduleCell::makeFiveMinutes($currentDateTime);
    $current_cell_id = strtotime($current_cell_id);
    
    if(empty($cell_id))
    {
        return $app->json(array("id" => $current_cell_id), 200);
    }else{

        if($cell_id!=$current_cell_id)
        {
            return $app->json(array("id" => $current_cell_id), 200);
        }else{

            $city_id = $current_city_id = \Hawkart\Megatv\GuestTable::getCity($guest_id);
            
            while($cell_id==$current_cell_id && $city_id==$current_city_id)
            {
                sleep(2);
                clearstatcache();
                $arDatetime = \CTimeEx::getDatetime();
                $currentDateTime = date("Y-m-d H:i:s", strtotime($arDatetime["SERVER_DATETIME_WITH_OFFSET"]));
                $current_cell_id = \Hawkart\Megatv\ScheduleCell::makeFiveMinutes($currentDateTime);
                $current_cell_id = strtotime($current_cell_id);
                $current_city_id = \Hawkart\Megatv\GuestTable::getCity($guest_id);
            }
            
            return $app->json(array("id" => $current_cell_id/*, "city_id" => $current_city_id, "g" => $guest_id*/), 200);
        }
    }
});

$app->get('/routes', function () use ($app) 
{
    echo "<table>";
    echo "<tr><td>=========</td> <td>========================================</td></tr>";
    echo "<tr><td>methods</td> <td>path</td></tr>";
    echo "<tr><td>=========</td> <td>========================================</td></tr>";
    
    foreach ($app['routes'] as $route) 
    {
        if($route->getPath()=="/routes") continue;
        
        echo "<tr><td>".implode('|', $route->getMethods())."</td> <td>/rest".$route->getPath()."</td></tr>";
    }
    echo "</table>";
    die();
});

//$app->run();
$app['http_cache']->run();