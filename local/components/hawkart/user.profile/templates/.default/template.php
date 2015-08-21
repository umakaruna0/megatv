<section class="user-profile">
	<div class="block-header">
		<h3 class="block-title">Ваш профиль</h3>
	</div>
	<div class="block-body">
		<div class="avatar-col">
			<div class="user-avatar-holder is-empty">
				<a href="#" class="load-avatar-link"><span>Загрузить аватар</span></a>
			</div>
			<span class="user-name"><?=trim($arResult["USER"]["NAME"]." ".$arResult["USER"]["LAST_NAME"])?></span>
			<span class="user-city">Санкт-Петербург</span>
		</div>
		<form action="<?= $templateFolder ?>/ajax.php" class="user-profile-form">
            <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
            <input type="hidden" name="action" value="profile"/>
            <?=bitrix_sessid_post()?>
			<div class="form-group">
				<label for="" class="sr-only">Ваше имя</label>
				<input type="text" name="USER[NAME]" id="" class="form-control" value="<?=$arResult["USER"]["NAME"]?>" placeholder="Ваше имя">
			</div>
			<div class="form-group">
				<label for="" class="sr-only">Ваша фамилия</label>
				<input type="text" name="USER[LAST_NAME]" id="" class="form-control" value="<?=$arResult["USER"]["LAST_NAME"]?>" placeholder="Ваша фамилия">
			</div>
			<div class="form-group">
				<label for="" class="sr-only">Ваше отчество</label>
				<input type="text" name="USER[SECOND_NAME]" id="" class="form-control" value="<?=$arResult["USER"]["SECOND_NAME"]?>" placeholder="Ваше отчество">
			</div>
			<div class="form-group has-feedback">
				<label for="" class="sr-only">Дата рождения</label>
				<input type="text" name="USER[PERSONAL_BIRTHDAY]" id="" class="form-control" value="<?=$arResult["USER"]["PERSONAL_BIRTHDAY"]?>" placeholder="Дата рождения">
				<span class="form-control-feedback"><span data-icon="icon-calendar"></span></span>
			</div>
			<div class="form-group">
				<label for="" class="sr-only">E-mail</label>
				<input type="text" name="USER[EMAIL]" id="" class="form-control" value="<?=$arResult["USER"]["EMAIL"]?>" placeholder="E-mail">
			</div>
			<div class="form-group">
				<label for="" class="sr-only">Телефон</label>
				<input type="text" name="USER[PERSONAL_PHONE]" id="" class="form-control" value="<?=$arResult["USER"]["PERSONAL_PHONE"]?>" placeholder="Телефон">
			</div>
			<button type="submit" class="btn btn-primary btn-block">Сохранить изменения</button>
		</form>
	</div>
</section>
<section class="user-passport">
	<div class="block-header">
		<h3 class="block-title">Паспортные данные</h3>
	</div>
	<div class="block-body">
		<form action="<?= $templateFolder ?>/ajax.php" class="user-passport-form">
            <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
            <input type="hidden" name="action" value="passport"/>
            <?=bitrix_sessid_post()?>
			<div class="flex-row passport-number-row">
				<div class="form-group">
					<label for="" class="sr-only">Серия паспорта</label>
					<input type="text" name="USER[PASSPORT][SERIA]" id="" class="form-control" placeholder="Серия" value="<?=$arResult["USER"]["PASSPORT"]["SERIA"]?>">
				</div>
				<div class="form-group">
					<label for="" class="sr-only">Номер паспорта</label>
					<input type="text" name="USER[PASSPORT][NUMBER]" id="" class="form-control" placeholder="Номер" value="<?=$arResult["USER"]["PASSPORT"]["NUMBER"]?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="sr-only">Кем выдан паспорт</label>
				<textarea name="USER[PASSPORT][WHO_ISSUED]" id="" rows="4" class="form-control" placeholder="Кем выдан"><?=$arResult["USER"]["PASSPORT"]["PREVIEW_TEXT"]?></textarea>
			</div>
			<div class="flex-row passport-additional-data-row">
				<div class="form-group has-feedback">
					<label for="" class="sr-only">Дата выдачи</label>
					<input type="text" name="USER[PASSPORT][WHEN_ISSUED]" id="" class="form-control" placeholder="Когда выдан" value="<?=$arResult["USER"]["PASSPORT"]["PROPERTY_WHEN_ISSUED_VALUE"]?>">
					<span class="form-control-feedback"><span data-icon="icon-calendar"></span></span>
				</div>
				<div class="form-group">
					<label for="" class="sr-only">Код подразделения</label>
					<input type="text" name="USER[PASSPORT][CODE_DIVISION]" id="" class="form-control" placeholder="Код подразделения" value="<?=$arResult["USER"]["PASSPORT"]["PROPERTY_CODE_DIVISION_VALUE"]?>">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="sr-only">Адрес прописки</label>
				<textarea name="USER[PASSPORT][ADDRESS]" id="" rows="4" class="form-control" placeholder="Адрес прописки"><?=$arResult["USER"]["PASSPORT"]["DETAIL_TEXT"]?></textarea>
			</div>
			<button type="submit" class="btn btn-primary btn-block">Сохранить данные</button>
		</form>
	</div>
</section>