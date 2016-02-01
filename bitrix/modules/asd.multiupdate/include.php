<?php
IncludeModuleLangFile(__FILE__);

class CASDMultiupdate {
	public static function OnAdminTabControlBegin(&$form) {
		if ($GLOBALS['APPLICATION']->GetCurPage()=='/bitrix/admin/update_system_partner.php') {
			ob_start();
			global $APPLICATION;
			?>
			<tr>
				<td colspan="2">
					<textarea id="multiupdates_area" rows="10" cols="75"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<script type="text/javascript">
						function InstallMultiUpdates() {
							var url = '/bitrix/admin/update_system_partner.php?addmodule=';
							var text = BX('multiupdates_area').value;
							var regex = /marketplace.1c-bitrix.ru\/solutions\/([a-zA-Z0-9.]+)/g;
							while (match = regex.exec(text)) {
								url += match[1]+',';
							}
							window.location.href = url;
						}
					</script>
					<input type="button" onclick="InstallMultiUpdates();" value="<?= GetMessage('ASD_MULTIUPDATE_BUTTON')?>" />
				</td>
			</tr>
			<?
			$strContent = ob_get_contents();
			ob_end_clean();
			$form->tabs[] = array('DIV' => 'asd_multiupdate', 'TAB' => GetMessage('ASD_MULTIUPDATE_TAB'),
															'TITLE' => GetMessage('ASD_MULTIUPDATE_TITLE'), 'CONTENT'=> $strContent);
		}
	}
}