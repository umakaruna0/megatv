<?
IncludeModuleLangFile(__FILE__);

class CASDmoney {
	public static function OnSalePayOrder($ID, $val) {
		$ID = intval($ID);
		if ($ID>0 && $val=='Y') {
			$arOrder = array();
			$dbBasketItems = CSaleBasket::GetList(array(), array('ORDER_ID' => $ID), false, false, array('ID', 'MODULE', 'CATALOG_XML_ID', 'QUANTITY'));
			while ($arItems = $dbBasketItems->Fetch()) {
				if ($arItems['MODULE']=='asd.money' && !empty($arItems['CATALOG_XML_ID']) && strpos($arItems['CATALOG_XML_ID'], '@')!==false) {
					if (empty($arOrder)) {
						$rsOrders = CSaleOrder::GetList(array(), array('ID' => $ID), false, false, array('ID', 'USER_ID'));
						$arOrder = $rsOrders->Fetch();
						if (empty($arOrder)) {
							return;
						}
					}
					list($amount, $curr) = explode('@', $arItems['CATALOG_XML_ID']);
					CSaleUserAccount::UpdateAccount($arOrder['USER_ID'],
													doubleval($amount) * doubleval($arItems['QUANTITY']),
													$curr,
													GetMessage('ASD_MODULE_TRANSACT_PREPAID'),
													$ID);
				}
			}
		}
	}
}
?>