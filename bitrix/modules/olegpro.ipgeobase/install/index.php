<?php
/**
 * Created by olegpro.ru.
 * User: Oleg Maksimenko <oleg.39style@gmail.com>
 * Date: 28.03.2015
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\HttpClient;

Loc::loadMessages(__FILE__);

class olegpro_ipgeobase extends CModule
{

    const ARCHIVE_URL = 'http://ipgeobase.ru/files/db/Main/geo_files.zip';

    var $MODULE_ID = 'olegpro.ipgeobase';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $strError = '';

    public $DIR_MODULE = 'bitrix';

    function olegpro_ipgeobase()
    {
        $arModuleVersion = array();
        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path . '/version.php');

        if (strpos(__FILE__, $_SERVER['DOCUMENT_ROOT'] . '/local/') === 0) {
            $this->DIR_MODULE = 'local';
        }

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = Loc::getMessage('OLEGPRO_IPGEOBASE_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('OLEGPRO_IPGEOBASE_MODULE_DESCRIPTION');

        $this->PARTNER_NAME = GetMessage('OLEGPRO_IPGEOBASE_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('OLEGPRO_IPGEOBASE_PARTNER_URI');
    }

    function GetModuleTasks()
    {
        return array();
    }

    function InstallDB($arParams = array())
    {
        global $DB, $DBType, $APPLICATION;

        $this->InstallTasks();
        ModuleManager::registerModule($this->MODULE_ID);
        Loader::includeModule($this->MODULE_ID);

        return true;
    }

    function UnInstallDB($arParams = array())
    {
        global $DB, $DBType, $APPLICATION;
        $this->errors = false;

        ModuleManager::unRegisterModule($this->MODULE_ID);

        if ($this->errors !== false) {
            $APPLICATION->ThrowException(implode('<br>', $this->errors));
            return false;
        }
        return true;
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function InstallFiles($arParams = array())
    {
        global $APPLICATION;

        $archive = $this->getArchive();
        if ($archive == false) {
            // Copy from a local copy
            $this->errors = false;
            $fileName = pathinfo(self::ARCHIVE_URL, PATHINFO_BASENAME);
            $archiveLocal = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->DIR_MODULE . '/modules/' . $this->MODULE_ID . '/install/data/' . $fileName;
            $archive = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/tmp/' . $fileName;
            CopyDirFiles($archiveLocal, $archive, true, true);
        }

        list($dirName, , , $fileName) = array_values(pathinfo($archive));

        require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/zip.php';

        $toDirData = $_SERVER['DOCUMENT_ROOT'] . '/' . $this->DIR_MODULE . '/modules/' . $this->MODULE_ID . '/data';

        $zip = new CZip($archive);
        if (!$zip->Unpack(sprintf('%s/%s/', $dirName, $fileName))) {
            $this->errors[] = $zip->GetErrors();
        } else {
            if(CheckDirPath($toDirData . '/')) {
                if (!CopyDirFiles(sprintf('%s/%s', $dirName, $fileName), $toDirData, true, true)) {
                    $this->errors[] = Loc::getMessage('OLEGPRO_IPGEOBASE_INSTALL_ERROR_COPY_IN_DIR', array(
                        '#FROM_DIR#' => sprintf('%s/%s/', $dirName, $fileName),
                        '#TO_DIR#' => $_SERVER['DOCUMENT_ROOT'] . '/' . $this->DIR_MODULE . '/modules/' . $this->MODULE_ID . '/data',
                    ));
                }
            }else{
                $this->errors[] = Loc::getMessage('OLEGPRO_IPGEOBASE_INSTALL_ERROR_NOT_WRITE', array(
                    '#TO_DIR#' => $toDirData,
                ));
            }
        }

        return true;
    }

    function UnInstallFiles()
    {
        return true;
    }

    function DoInstall()
    {
        global $USER, $APPLICATION;

        $this->errors = false;

        if ($USER->IsAdmin()) {
            if ($this->InstallDB()) {
                $this->InstallEvents();
                $this->InstallFiles();
            }
            $GLOBALS['errors'] = $this->errors;

            if ($this->errors !== false) {
                ModuleManager::unRegisterModule($this->MODULE_ID);
                $APPLICATION->ThrowException(implode('<br>', $this->errors));
                return false;
            }

        }
    }

    function DoUninstall()
    {
        global $DB, $USER, $DOCUMENT_ROOT, $APPLICATION, $step;

        if ($USER->IsAdmin()) {
            if ($this->UnInstallDB()) {
                $this->UnInstallEvents();
                $this->UnInstallFiles();
            }
            $GLOBALS['errors'] = $this->errors;
        }
    }

    /**
     * @return bool|string
     */
    protected function getArchive()
    {
        $client = new HttpClient();

        $fileName = pathinfo(self::ARCHIVE_URL, PATHINFO_BASENAME);

        if (!$client->download(self::ARCHIVE_URL, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/tmp/' . $fileName)) {
            $this->errors = $client->getError();
            return false;
        } else {
            return $_SERVER['DOCUMENT_ROOT'] . '/bitrix/tmp/' . $fileName;
        }

    }

}

?>