<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");

global $USER;
if(!is_object($USER))
    $USER = new CUser;

class CustomUploadHandler extends UploadHandler 
{
    protected function initialize() 
    {
        parent::initialize();
        global $USER;
    }

    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error, $index = null, $content_range = null) 
    {
        $file = parent::handle_file_upload(
            $uploaded_file, $name, $size, $type, $error, $index, $content_range
        );
        
        if (empty($file->error)) 
        {
            global $USER;
            
            $uid = $USER->GetID();
            $rsUser = CUser::GetByID($uid);
            $arUser = $rsUser->Fetch();
            
            $arFile = CFile::MakeFileArray($_SERVER['DOCUMENT_ROOT']."/upload/avatar/thumbnail/".$file->name);
            $arFile['del'] = "Y";           
            $arFile['old_file'] = $arUser['PERSONAL_PHOTO']; 
            $arFile["MODULE_ID"] = "main";
            $fields['PERSONAL_PHOTO'] = $arFile;
            $cuser = new CUser;
            $cuser->Update($uid, $fields);
        }
        return $file;
    }
}

$options = array ('upload_dir' => $_SERVER['DOCUMENT_ROOT']. '/upload/avatar/');
$upload_handler = new CustomUploadHandler($options);

die();
?>