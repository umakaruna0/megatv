<?php if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule('sale') || !CModule::IncludeModule('currency')) {
	ShowError(GetMessage('SPT_SALE_NOT_INSTALLED'));
	return;
}

if ($USER->GetID() <= 0) {
	$APPLICATION->AuthForm(GetMessage('SPT_ACCESS_DENIED'));
	return;
}

if (!is_array($arParams['ALLOWED_CURRENCY'])) {
	$arParams['ALLOWED_CURRENCY'] = array();
}
$arParams['COMISSION'] = round($arParams['COMISSION'], 2);
$arParams['CART_PAGE'] = trim($arParams['CART_PAGE']);
$arParams['PAY_IMMED'] = isset($arParams['PAY_IMMED']) && $arParams['PAY_IMMED']=='Y';

$arResult = array();
$arResult['ACCOUNT'] = array();
$arResult['CURRENCIES'] = array();
$arResult['ERROR'] = '';
$arResult['LANG_CURRENCY'] = CSaleLang::GetLangCurrency(SITE_ID);
$arResult['REQUEST_AMOUNT'] = $_REQUEST['amount']>0 ? round(str_replace(',', '.', trim($_REQUEST['amount'])), 2) : '';
$arResult['REQUEST_ACCOUNT'] = isset($_REQUEST['account']) ? $_REQUEST['account'] : $arParams['DEFAULT_CURRENCY'];
$arResult['REQUEST_PAY_SYSTEM'] = isset($_REQUEST['pay_system']) ? $_REQUEST['pay_system'] : 0;

$i = 0;
$rsCurrency = CCurrency::GetList(($by='name'), ($order='desc'), LANGUAGE_ID);
while ($arCurrency = $rsCurrency->Fetch()) {
	$arCurrency['ID'] = ++$i;
	if ($arCurrency['CURRENCY'] != $arResult['LANG_CURRENCY']) {
		$arCurrency['FACTOR'] = round(CCurrencyRates::GetConvertFactor($arCurrency['CURRENCY'], $arResult['LANG_CURRENCY']), 2);
	} else {
		$arCurrency['FACTOR'] = 1;
	}
	$arResult['CURRENCIES'][$arCurrency['CURRENCY']] = $arCurrency;
	if (empty($arParams['ALLOWED_CURRENCY']) || in_array($arCurrency['CURRENCY'], $arParams['ALLOWED_CURRENCY'])) {
		$arResult['ACCOUNT'][$arCurrency['CURRENCY']] = array();
	}
}

if ($arParams['PAY_IMMED']) {
	if (isset($arParams['PERSON_TYPE']) && $arParams['PERSON_TYPE']>0) {
		$arResult['PAY_SYSTEMS'] = array();
		$dbPaySysAction = CSalePaySystemAction::GetList(array(), array('PS_ACTIVE' => 'Y', 'PERSON_TYPE_ID' => $arParams['PERSON_TYPE']), false, false, array('*'));
		while ($arPaySysAction = $dbPaySysAction->fetch()) {
			if (intval($arPaySysAction['LOGOTIP']) > 0) {
				$arPaySysAction['LOGOTIP'] = CFile::GetFileArray($arPaySysAction['LOGOTIP']);
			}
			$arResult['PAY_SYSTEMS'][$arPaySysAction['PAY_SYSTEM_ID']] = $arPaySysAction;
		}
	} else {
		$arParams['PAY_IMMED'] = false;
	}
}

$rsAcc = CSaleUserAccount::GetList(
							array('CURRENCY' => 'ASC'),
							array('USER_ID' => $USER->GetID()),
							false, false, array('ID', 'CURRENT_BUDGET', 'CURRENCY', 'LOCKED'));
while ($arAcc = $rsAcc->GetNext(true, false))
{
	if (empty($arParams['ALLOWED_CURRENCY']) || in_array($arAcc['CURRENCY'], $arParams['ALLOWED_CURRENCY']))
	{
		$arAcc['CURRENT_BUDGET_FORMATED'] = SaleFormatCurrency($arAcc['CURRENT_BUDGET'], $arAcc['CURRENCY']);
		$arResult['ACCOUNT'][$arAcc['CURRENCY']] = $arAcc;
	}
}

foreach ($arResult['ACCOUNT'] as $curr => &$arAcc)
{
	if (empty($arAcc))
		$arAcc = array('CURRENT_BUDGET' => 0.0000, 'CURRENCY' => $curr, 'CURRENT_BUDGET_FORMATED' => SaleFormatCurrency(0, $curr));
}

if ($_REQUEST['prepaid_money']!='' && check_bitrix_sessid())
{
	$arResult['MONEY_OFF'] = round($arResult['REQUEST_AMOUNT'] + $arResult['REQUEST_AMOUNT']/100*$arParams['COMISSION'], 2);

	if ($arResult['REQUEST_AMOUNT']<=0 || $arResult['REQUEST_ACCOUNT']=='')
		$arResult['ERROR'] = GetMessage('SPT_ERROR_REQUIRED_AMOUNT');

	if ($arResult['ERROR']=='' && !isset($arResult['ACCOUNT'][$arResult['REQUEST_ACCOUNT']]))
		$arResult['ERROR'] = GetMessage('SPT_ERROR_CURR');

	if ($arParams['PAY_IMMED'] && (!$arResult['REQUEST_PAY_SYSTEM'] || !isset($arResult['PAY_SYSTEMS'][$arResult['REQUEST_PAY_SYSTEM']]))) {
		$arResult['ERROR'] = GetMessage('SPT_ERROR_PAY_SYSTEM');
	}

	if ($arResult['ERROR'] == '')
	{

		$dbBaket = CSaleBasket::GetList(array(),array('FUSER_ID' => CSaleBasket::GetBasketUserID(), 'LID' => SITE_ID, 'ORDER_ID' => 'NULL'));
		while ($arBasket = $dbBaket->GetNext())
		{
			if ($arBasket['CATALOG_XML_ID']!='' && strpos($arBasket['CATALOG_XML_ID'], '@')!==false)
			{
				list($amount, $curr) = explode('@', $arBasket['CATALOG_XML_ID']);
				if ($curr == $arResult['REQUEST_ACCOUNT'])
					CSaleBasket::Delete($arBasket['ID']);
			}
		}
		if ($arParams['PAY_IMMED']) {
			CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
		}
		CSaleBasket::Add(array(
							'PRODUCT_ID' => $arResult['CURRENCIES'][$arResult['REQUEST_ACCOUNT']]['ID'],
							'PRICE' => CCurrencyRates::ConvertCurrency($arResult['MONEY_OFF'], $arResult['REQUEST_ACCOUNT'], $arResult['LANG_CURRENCY']),
							'CURRENCY' => $arResult['LANG_CURRENCY'],//$arResult['REQUEST_ACCOUNT'],
							'QUANTITY' => 1,
							'LID' => LANG,
							'DELAY' => 'N',
							'CAN_BUY' => 'Y',
							'NAME' => GetMessage('SPT_NAME_IN_CART', array('#VALUE#' => SaleFormatCurrency($arResult['REQUEST_AMOUNT'], $arResult['REQUEST_ACCOUNT']))),
							'MODULE' => 'asd.money',
							'DETAIL_PAGE_URL' => '',
							'CATALOG_XML_ID' => $arResult['REQUEST_AMOUNT'] .'@'. $arResult['REQUEST_ACCOUNT'],
						));

		if ($arParams['PAY_IMMED']) {
			$ORDER_ID = CSaleOrder::Add(array(
					'LID' => SITE_ID,
					'PERSON_TYPE_ID' => $arParams['PERSON_TYPE'],
					'PRICE' => CCurrencyRates::ConvertCurrency($arResult['MONEY_OFF'], $arResult['REQUEST_ACCOUNT'], $arResult['LANG_CURRENCY']),
					'CURRENCY' => $arResult['LANG_CURRENCY'],//$arResult['REQUEST_ACCOUNT'],
					'PAY_SYSTEM_ID' => $arResult['REQUEST_PAY_SYSTEM'],
					'USER_ID' => $USER->getID(),
				));
			if ($ORDER_ID > 0) {
				$arOrder = CSaleOrder::GetByID($ORDER_ID);
				CSaleBasket::OrderBasket($ORDER_ID);
			}
			$arPaySysAction = $arResult['PAY_SYSTEMS'][$arResult['REQUEST_PAY_SYSTEM']];
			if (strlen($arPaySysAction['ACTION_FILE']) > 0) {
				CSalePaySystemAction::InitParamArrays($arOrder, $ORDER_ID, $arPaySysAction['PARAMS']);
				$pathToAction = $_SERVER['DOCUMENT_ROOT'].$arPaySysAction['ACTION_FILE'];
				$pathToAction = rtrim(str_replace('\\', '/', $pathToAction), '/');
				if (file_exists($pathToAction)) {
					if (is_dir($pathToAction)) {
						if (file_exists($pathToAction.'/payment.php')) {
							include($pathToAction.'/payment.php');
						}
					} else {
						include($pathToAction);
					}
				}
				if(strlen($arPaySysAction['ENCODING']) > 0) {
					define('BX_SALE_ENCODING', $arPaySysAction['ENCODING']);
					AddEventHandler('main', 'OnEndBufferContent', 'ChangeEncoding');
					function ChangeEncoding($content) {
						global $APPLICATION;
						header('Content-Type: text/html; charset='.BX_SALE_ENCODING);
						$content = $APPLICATION->ConvertCharset($content, SITE_CHARSET, BX_SALE_ENCODING);
						$content = str_replace('charset='.SITE_CHARSET, 'charset='.BX_SALE_ENCODING, $content);
					}
				}
				return;
			}
		} else {
			LocalRedirect($arParams['CART_PAGE']);
		}
	}
}

if (!function_exists('asd_cmp_account'))
{
	function asd_cmp_account($a, $b)
	{
		if ($a['CURRENT_BUDGET'] == $b['CURRENT_BUDGET'])
			return 0;
		return ($a['CURRENT_BUDGET'] < $b['CURRENT_BUDGET']) ? 1 : -1;
	}
}
usort($arResult['ACCOUNT'], 'asd_cmp_account');

if ($arParams['SET_TITLE'] == 'Y') {
	$APPLICATION->SetTitle(GetMessage('SPT_TITLE'));
}

$this->IncludeComponentTemplate();