<section class="reset-password">
	<div class="block-header">
		<h3 class="block-title">Смена пароля</h3>
	</div>
	<div class="block-body">
		<form action="<?= $templateFolder ?>/ajax.php" class="reset-password-form" data-module="reset-password-form">
            <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
            <?=bitrix_sessid_post()?>
			<div class="col">
				<div class="form-group">
					<label for="" class="sr-only">Старый пароль</label>
					<input type="text" name="old-password" id="" class="form-control" placeholder="Старый пароль">
				</div>
				<div class="form-group">
					<label for="" class="sr-only">Новый пароль еще раз</label>
					<input type="text" name="new-password" id="" class="form-control" placeholder="Новый пароль еще раз">
				</div>
			</div>
			<div class="col">
				<div class="form-group">
					<label for="" class="sr-only">Новый пароль</label>
					<input type="text" name="new-password2" id="" class="form-control" placeholder="Новый пароль">
				</div>
				<button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistates-button"><span class="default-state init-state">Сменить пароль</span><span class="done-state"><span data-icon="icon-msbutton-checkmark"></span>Пароль изменён</span></button>
			</div>
		</form>
	</div>
</section>