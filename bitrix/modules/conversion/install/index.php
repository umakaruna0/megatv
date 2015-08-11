<?php

global $MESS;

use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (class_exists('conversion')) return;

Class conversion extends CModule
{
	var $MODULE_ID = 'conversion';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = 'N';

	function conversion()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

		$this->MODULE_NAME = Loc::getMessage('CONVERSION_MODULE_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('CONVERSION_MODULE_DESC');
	}

	function InstallDB($arParams = array())
	{
		ModuleManager::registerModule('conversion');

		global $DB;
		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/conversion/install/db/'.strtolower($DB->type).'/install.sql');

		RegisterModuleDependences('conversion', 'OnGetCounterTypes'        , 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onGetCounterTypes'        );
		RegisterModuleDependences('conversion', 'OnGetAttributeTypes'      , 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onGetAttributeTypes'      );
		RegisterModuleDependences('conversion', 'OnGetAttributeGroupTypes' , 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onGetAttributeGroupTypes' );
		RegisterModuleDependences('conversion', 'OnSetDayContextAttributes', 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onSetDayContextAttributes');
		RegisterModuleDependences('main'      , 'OnProlog'                 , 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onProlog'                 );

		if (Option::get('conversion', 'START_DATE_TIME', 'undefined') == 'undefined')
		{
			Option::set('conversion', 'START_DATE_TIME', date('Y-m-d H:i:s'));
		}

		return true;
	}

	function UnInstallDB($arParams = array())
	{
		UnRegisterModuleDependences('conversion', 'OnGetCounterTypes'        , 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onGetCounterTypes'        );
		UnRegisterModuleDependences('conversion', 'OnGetAttributeTypes'      , 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onGetAttributeTypes'      );
		UnRegisterModuleDependences('conversion', 'OnGetAttributeGroupTypes' , 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onGetAttributeGroupTypes' );
		UnRegisterModuleDependences('conversion', 'OnSetDayContextAttributes', 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onSetDayContextAttributes');
		UnRegisterModuleDependences('main'      , 'OnProlog'                 , 'conversion', '\Bitrix\Conversion\Internals\Handlers', 'onProlog'                 );

		global $DB;
		$DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/conversion/install/db/'.strtolower($DB->type).'/uninstall.sql');

		ModuleManager::unRegisterModule('conversion');

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
		if ($_ENV['COMPUTERNAME'] != 'BX')
		{
			$root = $_SERVER['DOCUMENT_ROOT'];
			CopyDirFiles($root.'/bitrix/modules/conversion/install/admin' , $root.'/bitrix/admin' , true, true);
			CopyDirFiles($root.'/bitrix/modules/conversion/install/tools' , $root.'/bitrix/tools' , true, true);
			CopyDirFiles($root.'/bitrix/modules/conversion/install/js'    , $root.'/bitrix/js'    , true, true);
			CopyDirFiles($root.'/bitrix/modules/conversion/install/themes', $root.'/bitrix/themes', true, true);
			CopyDirFiles($root.'/bitrix/modules/conversion/install/images', $root.'/bitrix/images', true, true);
		}

		return true;
	}

	function UnInstallFiles()
	{
		if ($_ENV['COMPUTERNAME'] != 'BX')
		{
			$root = $_SERVER['DOCUMENT_ROOT'];
			DeleteDirFiles($root.'/bitrix/modules/conversion/install/admin' , $root.'/bitrix/admin' );
			DeleteDirFiles($root.'/bitrix/modules/conversion/install/tools' , $root.'/bitrix/tools' );
			DeleteDirFiles($root.'/bitrix/modules/conversion/install/js'    , $root.'/bitrix/js'    );
			DeleteDirFiles($root.'/bitrix/modules/conversion/install/themes', $root.'/bitrix/themes');
			DeleteDirFiles($root.'/bitrix/modules/conversion/install/images', $root.'/bitrix/images');
		}

		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;

		$this->InstallDB();
		$this->InstallFiles();

		$APPLICATION->IncludeAdminFile(Loc::getMessage('CONVERSION_INSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/conversion/install/step.php');
	}

	function DoUninstall()
	{
		global $APPLICATION;

		$this->UnInstallDB();
		$this->UnInstallFiles();

		$APPLICATION->IncludeAdminFile(Loc::getMessage('CONVERSION_UNINSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/conversion/install/unstep.php');
	}
}

