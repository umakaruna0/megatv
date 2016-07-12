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
$arParams['PATH_TO_USER'] = trim($arParams['PATH_TO_USER']);
$arParams['COMISSION'] = round($arParams['COMISSION'], 2);

$arResult = array();
$arResult['FROM_ACCOUNT'] = array();
$arResult['TO_USER'] = array();
$arResult['CURRENCIES'] = array();
$arResult['ERROR'] = '';
$arResult['MONEY_OFF'] = 0;
$arResult['REQUEST_AMOUNT'] = $_REQUEST['amount']>0 ? round(str_replace(',', '.', trim($_REQUEST['amount'])), 2) : '';
$arResult['REQUEST_USER'] = htmlspecialcharsbx(trim($_REQUEST['user']));
$arResult['~REQUEST_COMMENT'] = trim($_REQUEST['comment']);
$arResult['REQUEST_COMMENT'] = htmlspecialcharsbx($arResult['~REQUEST_COMMENT']);
$arResult['REQUEST_ACCOUNT'] = isset($_REQUEST['account']) ? $_REQUEST['account'] : $arParams['DEFAULT_CURRENCY'];
$arResult['SUCCESS'] = isset($_REQUEST['success'])&&!strlen($_REQUEST['send_money']) ? 'Y' : 'N';

$rsCurrency = CCurrency::GetList(($by='name'), ($order='desc'), LANGUAGE_ID);
while ($arCurrency = $rsCurrency->Fetch()) {
	$arResult['CURRENCIES'][$arCurrency['CURRENCY']] = $arCurrency;
	if (empty($arParams['ALLOWED_CURRENCY']) || in_array($arCurrency['CURRENCY'], $arParams['ALLOWED_CURRENCY'])) {
		$arResult['FROM_ACCOUNT'][$arCurrency['CURRENCY']] = array();
	}
}

$rsAcc = CSaleUserAccount::GetList(
							array('CURRENCY' => 'ASC'),
							array('USER_ID' => $USER->GetID()),
							false, false, array('ID', 'CURRENT_BUDGET', 'CURRENCY'));
while ($arAcc = $rsAcc->GetNext(true, false)) {
	if (empty($arParams['ALLOWED_CURRENCY']) || in_array($arAcc['CURRENCY'], $arParams['ALLOWED_CURRENCY'])) {
		$arAcc['CURRENT_BUDGET_FORMATED'] = SaleFormatCurrency($arAcc['CURRENT_BUDGET'], $arAcc['CURRENCY']);
		$arResult['FROM_ACCOUNT'][$arAcc['CURRENCY']] = $arAcc;
	}
}

foreach ($arResult['FROM_ACCOUNT'] as $curr => &$arAcc) {
	if (empty($arAcc)) {
		$arAcc = array('CURRENT_BUDGET' => 0.0000, 'CURRENCY' => $curr, 'CURRENT_BUDGET_FORMATED' => SaleFormatCurrency(0, $curr));
	}
}

if (strlen($_REQUEST['send_money']) && check_bitrix_sessid()) {
	$arResult['MONEY_OFF'] = round($arResult['REQUEST_AMOUNT'] + $arResult['REQUEST_AMOUNT']/100*$arParams['COMISSION'], 2);
	if ($arResult['REQUEST_AMOUNT']<=0 || !strlen($arResult['REQUEST_USER']) || !strlen($arResult['REQUEST_ACCOUNT'])) {
		$arResult['ERROR'] = GetMessage('SPT_ERROR_REQUIRED_FIELDS');
	}
	if (!strlen($arResult['ERROR']) && $arResult['FROM_ACCOUNT'][$arResult['REQUEST_ACCOUNT']]['CURRENT_BUDGET']<$arResult['MONEY_OFF']) {
		$arResult['ERROR'] = GetMessage('SPT_ERROR_NOT_ENOUGH');
	}
	if (!strlen($arResult['ERROR']) &&
		(!($arUser = CUser::GetByLogin($arResult['REQUEST_USER'])->Fetch())) &&
		(!($arUser = CUser::GetByID($arResult['REQUEST_USER'])->Fetch()))
	) {
		$arResult['ERROR'] = GetMessage('SPT_ERROR_USER_NOT_FOUND');
	}
	if (!strlen($arResult['ERROR']) && $arUser['ID']==$USER->GetID()) {
		$arResult['ERROR'] = GetMessage('SPT_ERROR_CANNT_YOURSELF');
	}
	if (!strlen($arResult['ERROR'])) {
		$arResult['TO_USER'] = $arUser;
		if (strlen($_REQUEST['send_money_now'])) {
			$arUserCurr = CUser::GetByID($USER->GetID())->Fetch();
			CSaleUserAccount::UpdateAccount($USER->GetID(),
											-$arResult['MONEY_OFF'],
											$arResult['REQUEST_ACCOUNT'],
											GetMessage('SPT_TRANSACT_DESC_FROM', $arUser),
											0,
											$arResult['~REQUEST_COMMENT']);
			CSaleUserAccount::UpdateAccount($arUser['ID'],
											$arResult['REQUEST_AMOUNT'],
											$arResult['REQUEST_ACCOUNT'],
											GetMessage('SPT_TRANSACT_DESC_TO', $arUserCurr),
											0,
											$arResult['~REQUEST_COMMENT']);
			if ($arParams['NOTIFY_USER']=='Y' && CModule::IncludeModule('socialnetwork')) {
				$letter = GetMessage('SPT_MESS_DESC_TO', array_merge($arUserCurr, array('SUM' => SaleFormatCurrency($arResult['REQUEST_AMOUNT'], $arResult['REQUEST_ACCOUNT']))));
				if (strlen($arResult['~REQUEST_COMMENT'])) {
					$letter .= ' ('.$arResult['~REQUEST_COMMENT'].')';
				}
				CSocNetMessages::Add(array(
									'FROM_USER_ID' => $USER->GetID(),
									'TO_USER_ID' => $arUser['ID'],
									'MESSAGE' => $letter,
									'=DATE_CREATE' => 'now()',
									'MESSAGE_TYPE' => 'S',
									'FROM_DELETED' => 'N',
									'TO_DELETED' => 'N',
									'SEND_MAIL' => 'Y',
								));
			}
			LocalRedirect($APPLICATION->GetCurPageParam('success', array('success')));
		}
	}
}

if (!function_exists('asd_cmp_account')) {
	function asd_cmp_account($a, $b) {
		if ($a['CURRENT_BUDGET'] == $b['CURRENT_BUDGET']) {
			return 0;
		}
		return ($a['CURRENT_BUDGET'] < $b['CURRENT_BUDGET']) ? 1 : -1;
	}
}
usort($arResult['FROM_ACCOUNT'], 'asd_cmp_account');

if ($arParams['SET_TITLE'] == 'Y') {
	$APPLICATION->SetTitle(GetMessage('SPT_TITLE'));
}

$this->IncludeComponentTemplate();