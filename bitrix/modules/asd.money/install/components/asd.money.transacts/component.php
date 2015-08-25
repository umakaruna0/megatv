<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if ($_REQUEST['reset']!='')
	LocalRedirect($APPLICATION->GetCurPageParam('', array('currency', 'user', 'reset'), false));

if(!CModule::IncludeModule('sale'))
{
	ShowError(GetMessage('SPT_SALE_NOT_INSTALLED'));
	return;
}

if ($USER->GetID() <= 0)
{
	$APPLICATION->AuthForm(GetMessage('SPT_ACCESS_DENIED'));
	return;
}

$arResult = array();
$arResult['ITEMS'] = array();
$arResult['NAV_STRING'] = '';
$arResult['CURRENCY'] = array();
$arResult['REQUEST_CURR'] = $_REQUEST['currency'];
$arResult['REQUEST_DATE_FROM'] = trim($_REQUEST['date_from']);
$arResult['REQUEST_DATE_TO'] = trim($_REQUEST['date_to']);

if (CModule::IncludeModule('currency'))
{
	$rsCurrency = CCurrency::GetList(($by='name'), ($order='desc'), LANGUAGE_ID);
	while ($arCurrency = $rsCurrency->Fetch())
		$arResult['CURRENCY'][$arCurrency['CURRENCY']] = $arCurrency['FULL_NAME'];

	if (count($arResult['CURRENCY']) == 1)
		$arResult['CURRENCY'] = array();
}

$arFilter = array('USER_ID' => $USER->GetID());
if ($arResult['REQUEST_CURR'] != '')
	$arFilter['CURRENCY'] = $arResult['REQUEST_CURR'];
if ($arResult['REQUEST_DATE_FROM'] != '')
	$arFilter['>=TRANSACT_DATE'] = $arResult['REQUEST_DATE_FROM'];
if ($arResult['REQUEST_DATE_TO'] != '')
	$arFilter['<=TRANSACT_DATE'] = $arResult['REQUEST_DATE_TO'];

$rsTransacts = CSaleUserTransact::GetList(
										array('TRANSACT_DATE' => 'DESC', 'ID' => 'DESC'),
										$arFilter,
										false, false,
										array('AMOUNT', 'CURRENCY', 'DEBIT', 'DESCRIPTION', 'NOTES', 'ORDER_ID', 'TRANSACT_DATE')
										);
$rsTransacts->NavStart($arParams['PAGE_COUNT']);
while ($arTransact = $rsTransacts->GetNext())
{
	$arTransact['AMOUNT_FORMATED'] = SaleFormatCurrency($arTransact['AMOUNT'], $arTransact['CURRENCY']);
	$arResult['ITEMS'][] = $arTransact;
}

$arResult['NAV_STRING'] = $rsTransacts->GetPageNavStringEx($navComponentObject, GetMessage('NP_TITLE'), $arParams['PAGER_TEMPLATE']);

if ($arParams['SET_TITLE'] == 'Y')
	$APPLICATION->SetTitle(GetMessage('SPT_TITLE'));

$this->IncludeComponentTemplate();
?>