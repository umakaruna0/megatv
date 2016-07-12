<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

$arCurrencyTypes = array('' => GetMessage('SPT_ALLOWED_CURRENCY_ALL'));
if (CModule::IncludeModule('currency')) {
	$rsCurrency = CCurrency::GetList(($by='name'), ($order='desc'), LANGUAGE_ID);
	while ($arCurrency = $rsCurrency->Fetch()) {
		$arCurrencyTypes[$arCurrency['CURRENCY']] = $arCurrency['FULL_NAME'];
	}
}

$arComponentParameters = array(
	'PARAMETERS' => array(
		'ALLOWED_CURRENCY' => Array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('SPT_ALLOWED_CURRENCY'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'VALUES' => $arCurrencyTypes,
		),
		'DEFAULT_CURRENCY' => Array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('SPT_DEFAULT_CURRENCY'),
			'TYPE' => 'LIST',
			'VALUES' => $arCurrencyTypes,
		),
		'COMISSION' => Array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('SPT_COMISSION'),
			'TYPE' => 'STRING',
			'DEFAULT' => '0',
			'COLS' => '5',
		),
		'PATH_TO_USER' => Array(
			'PARENT' => 'BASE',
			'NAME' => GetMessage('SPT_PATH_TO_USER'),
			'TYPE' => 'STRING',
			'DEFAULT' => '',
		),
		'SET_TITLE' => Array(),
	),
);

if (IsModuleInstalled('socialnetwork')) {
	$arComponentParameters['PARAMETERS']['NOTIFY_USER'] = array(
															'PARENT' => 'BASE',
															'NAME' => GetMessage('SPT_NOTIFY_USER'),
															'TYPE' => 'CHECKBOX',
															'DEFAULT' => 'Y'
														);
}