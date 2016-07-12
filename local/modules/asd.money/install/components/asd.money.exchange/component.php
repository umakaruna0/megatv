<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule('sale') || !CModule::IncludeModule('currency'))
{
	ShowError(GetMessage('SPT_SALE_NOT_INSTALLED'));
	return;
}

if ($USER->GetID() <= 0)
{
	$APPLICATION->AuthForm(GetMessage('SPT_ACCESS_DENIED'));
	return;
}

if (!is_array($arParams['ALLOWED_CURRENCY_FROM']))
	$arParams['ALLOWED_CURRENCY_FROM'] = array();
if (!is_array($arParams['ALLOWED_CURRENCY_TO']))
	$arParams['ALLOWED_CURRENCY_TO'] = array();
$arParams['COMISSION'] = round($arParams['COMISSION'], 2);

$arResult = array();
$arResult['FROM'] = array();
$arResult['TO'] = array();
$arResult['CURRENCIES'] = array();
$arResult['ERROR'] = '';
$arResult['REQUEST_AMOUNT'] = $_REQUEST['amount']>0 ? round(str_replace(',', '.', trim($_REQUEST['amount'])), 2) : '';
$arResult['REQUEST_FROM'] = $_REQUEST['from'];
$arResult['REQUEST_TO'] = $_REQUEST['to'];
$arResult['SUCCESS'] = isset($_REQUEST['success']) ? 'Y' : 'N';

$rsCurrency = CCurrency::GetList(($by='name'), ($order='desc'), LANGUAGE_ID);
while ($arCurrency = $rsCurrency->Fetch())
{
	$arResult['CURRENCIES'][$arCurrency['CURRENCY']] = $arCurrency;
	if (empty($arParams['ALLOWED_CURRENCY_FROM']) || in_array($arCurrency['CURRENCY'], $arParams['ALLOWED_CURRENCY_FROM']))
		$arResult['FROM'][$arCurrency['CURRENCY']] = array();
	if (empty($arParams['ALLOWED_CURRENCY_TO']) || in_array($arCurrency['CURRENCY'], $arParams['ALLOWED_CURRENCY_TO']))
		$arResult['TO'][$arCurrency['CURRENCY']] = $arCurrency['FULL_NAME'];
}

$rsAcc = CSaleUserAccount::GetList(
							array('CURRENCY' => 'ASC'),
							array('USER_ID' => $USER->GetID()),
							false, false, array('ID', 'CURRENT_BUDGET', 'CURRENCY'));
while ($arAcc = $rsAcc->GetNext(true, false))
{
	if (empty($arParams['ALLOWED_CURRENCY_FROM']) || in_array($arAcc['CURRENCY'], $arParams['ALLOWED_CURRENCY_FROM']))
	{
		$arAcc['CURRENT_BUDGET_FORMATED'] = SaleFormatCurrency($arAcc['CURRENT_BUDGET'], $arAcc['CURRENCY']);
		$arAcc['FULL_NAME'] = $arResult['CURRENCIES'][$arAcc['CURRENCY']]['FULL_NAME'];
		$arResult['FROM'][$arAcc['CURRENCY']] = $arAcc;
	}
}

foreach ($arResult['FROM'] as $curr => &$arAcc)
{
	if (empty($arAcc))
		$arAcc = array(
						'CURRENT_BUDGET' => 0.0000,
						'CURRENCY' => $curr,
						'FULL_NAME' => $arResult['CURRENCIES'][$curr]['FULL_NAME'],
						'CURRENT_BUDGET_FORMATED' => SaleFormatCurrency(0, $curr)
				);
}

if (strlen($_REQUEST['exchange_money']) && check_bitrix_sessid())
{
	$arResult['MONEY_OFF'] = round($arResult['REQUEST_AMOUNT'] + $arResult['REQUEST_AMOUNT']/100*$arParams['COMISSION'], 2);

	if ($arResult['REQUEST_AMOUNT']<=0 || !strlen($arResult['REQUEST_FROM']) || !strlen($arResult['REQUEST_TO']))
		$arResult['ERROR'] = GetMessage('SPT_ERROR_REQUIRED_FIELDS');

	if (!strlen($arResult['ERROR']) && $arResult['REQUEST_FROM']==$arResult['REQUEST_TO'])
		$arResult['ERROR'] = GetMessage('SPT_ERROR_CANNT_SAME_CURR');

	if (!strlen($arResult['ERROR']) && (!isset($arResult['FROM'][$arResult['REQUEST_FROM']]) || !isset($arResult['TO'][$arResult['REQUEST_TO']])))
		$arResult['ERROR'] = GetMessage('SPT_ERROR_CURR');

	if (!strlen($arResult['ERROR']) && $arResult['FROM'][$arResult['REQUEST_FROM']]['CURRENT_BUDGET']<$arResult['MONEY_OFF'])
		$arResult['ERROR'] = GetMessage('SPT_ERROR_NOT_ENOUGH');

	if ($arResult['ERROR'] == '')
	{
		CSaleUserAccount::UpdateAccount($USER->GetID(),
										-$arResult['MONEY_OFF'],
										$arResult['REQUEST_FROM'],
										GetMessage('SPT_TRANSACT_DESC_FROM', array('#CURR#' => $arResult['CURRENCIES'][$arResult['REQUEST_TO']]['FULL_NAME'])), 0);
		CSaleUserAccount::UpdateAccount($USER->GetID(),
										CCurrencyRates::ConvertCurrency($arResult['REQUEST_AMOUNT'], $arResult['REQUEST_FROM'], $arResult['REQUEST_TO']),
										$arResult['REQUEST_TO'],
										GetMessage('SPT_TRANSACT_DESC_TO', array('#CURR#' => $arResult['CURRENCIES'][$arResult['REQUEST_FROM']]['FULL_NAME'])), 0);
		LocalRedirect($APPLICATION->GetCurPageParam('success', array('success')));
	}
}

if ($arParams['SET_TITLE'] == 'Y')
	$APPLICATION->SetTitle(GetMessage('SPT_TITLE'));

$this->IncludeComponentTemplate();
?>