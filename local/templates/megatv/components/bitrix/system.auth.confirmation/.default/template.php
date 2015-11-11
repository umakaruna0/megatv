<form action="<?= $templateFolder ?>/ajax.php" data-type="signup-code-form">
    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
    <input type="hidden" name="USER_EMAIL" value="" data-type="adaptive-field-hidden" />
    <?=bitrix_sessid_post()?>
    
	<div class="form-group">
		<label for="" class="sr-only">Код активации</label>
		<input type="text" name="CHECKWORD" id="" class="form-control" placeholder="Код активации" data-type="code-field">
	</div>
	<div class="form-actions">
		<button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
			<span class="default-state init-state">Активировать</span>
			<span class="done-state">Активирую ваш аккаунт...</span>
			<span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
			<span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span>
		</button>
		<a href="#" class="form-subaction-link" data-type="signin-handler-link">У меня есть аккаунт</a>
	</div>
</form>