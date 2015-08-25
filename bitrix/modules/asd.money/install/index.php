<?
global $MESS;
$PathInstall = str_replace('\\', '/', __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen('/index.php'));
IncludeModuleLangFile($PathInstall.'/install.php');
include($PathInstall.'/version.php');

if (class_exists('asd_money')) return;

class asd_money extends CModule
{
	var $MODULE_ID = "asd.money";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $PARTNER_NAME;
	public $PARTNER_URI;
	public $MODULE_GROUP_RIGHTS = 'N';
	public $NEED_MAIN_VERSION = '';
	public $NEED_MODULES = array('sale');

	public function __construct()
	{
		$arModuleVersion = array();

		$path = str_replace('\\', '/', __FILE__);
		$path = substr($path, 0, strlen($path) - strlen('/index.php'));
		include($path.'/version.php');

		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->PARTNER_NAME = GetMessage("ASD_PARTNER_NAME");
		$this->PARTNER_URI = 'http://www.d-it.ru/';

		$this->MODULE_NAME = GetMessage('ASD_MODULE_MONEY_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('ASD_MODULE_MONEY_DESCRIPTION');
	}

	public function DoInstall()
	{
		if (is_array($this->NEED_MODULES) && !empty($this->NEED_MODULES))
			foreach ($this->NEED_MODULES as $module)
				if (!IsModuleInstalled($module))
					$this->ShowForm('ERROR', GetMessage('ASD_NEED_MODULES', array('#MODULE#' => $module)));

		if (strlen($this->NEED_MAIN_VERSION)<=0 || version_compare(SM_VERSION, $this->NEED_MAIN_VERSION)>=0)
		{
			CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/asd.money/install/components/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/', true, true);
			RegisterModuleDependences('sale', 'OnSalePayOrder', 'asd.money', 'CASDmoney', 'OnSalePayOrder');
			RegisterModule('asd.money');
			$this->ShowForm('OK', GetMessage('MOD_INST_OK'));
		}
		else
			$this->ShowForm('ERROR', GetMessage('ASD_NEED_RIGHT_VER', array('#NEED#' => $this->NEED_MAIN_VERSION)));
	}

	public function DoUninstall()
	{
		UnRegisterModuleDependences('sale', 'OnSalePayOrder', 'asd.money', 'CASDmoney', 'OnSalePayOrder');
		UnRegisterModule('asd.money');
		$this->ShowForm('OK', GetMessage('MOD_UNINST_OK'));
	}

	private function ShowForm($type, $message, $buttonName='')
	{
		$keys = array_keys($GLOBALS);
		for($i=0; $i<count($keys); $i++)
			if($keys[$i]!='i' && $keys[$i]!='GLOBALS' && $keys[$i]!='strTitle' && $keys[$i]!='filepath')
				global ${$keys[$i]};

		$PathInstall = str_replace('\\', '/', __FILE__);
		$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen('/index.php'));
		IncludeModuleLangFile($PathInstall.'/install.php');

		$APPLICATION->SetTitle(GetMessage('ASD_MODULE_MONEY_NAME'));
		include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');
		echo CAdminMessage::ShowMessage(array('MESSAGE' => $message, 'TYPE' => $type));
		?>
		<form action="<?= $APPLICATION->GetCurPage()?>" method="get">
		<p>
			<input type="hidden" name="lang" value="<?= LANG?>" />
			<input type="submit" value="<?= strlen($buttonName) ? $buttonName : GetMessage('MOD_BACK')?>" />
		</p>
		</form>
		<?
		include($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
		die();
	}
}
?>