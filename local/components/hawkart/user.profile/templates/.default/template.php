<section class="user-profile">
	<div class="block-header">
		<h3 class="block-title">Ваш профиль</h3>
	</div>
	<div class="block-body">
        <div class="avatar-col">
			<div class="user-avatar-holder<?if(!$arResult["USER"]["PERSONAL_PHOTO"]):?> is-empty<?endif;?>" data-module="avatar-loader">
				<script type="text/x-config">
					{
						"url": "<?= $templateFolder ?>/upload.php"
					}
				</script>
                
				<div class="progressbar-holder">
					<input type="file" name="files[]" id="_id-avatar-loader--file" data-type="file-input">
                    <?if($arResult["USER"]["PERSONAL_PHOTO"]):?>
                        <img src="<?=CFile::GetPath($arResult["USER"]["PERSONAL_PHOTO"])?>" alt="<?=$USER->GetFullName()?>">
                    <?endif;?>
				</div>
				<span class="load-avatar-text-holder load-avatar"><?if($arResult["USER"]["PERSONAL_PHOTO"]):?><span data-icon="icon-replace-avatar" class="load-avatar__icon"></span><span class="load-avatar__title">Обновить аватар</span><?else:?><span>Загрузить аватар</span><?endif;?></span>
			</div>
			<span class="user-name"><?=trim($arResult["USER"]["NAME"]." ".$arResult["USER"]["LAST_NAME"])?></span>
            <span class="user-city">
				<strong class="user-city__strong">Город: </strong>
				<span class="user-city__span"><?=$arParams["CITY_GEO"]["UF_TITLE"]?></span>
            </span>
		</div>
        
        <form action="<?= $templateFolder ?>/ajax.php" class="user-profile-form" data-module="user-profile-form">
            <script type="text/x-config">
				{
					"dateMask": "99/99/9999",
					"phoneMask": "+7 (999) 999-99-99"
				}
			</script>
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
				<input type="text" name="USER[PERSONAL_BIRTHDAY]" id="" class="form-control" value="<?=$arResult["USER"]["PERSONAL_BIRTHDAY"]?>" placeholder="Дата рождения" data-type="masked-birthdate-input">
			</div>
			<div class="form-group">
				<label for="" class="sr-only">E-mail</label>
				<input type="text" name="USER[EMAIL]" id="" class="form-control" value="<?=$arResult["USER"]["EMAIL"]?>" placeholder="E-mail">
			</div>
			<div class="form-group">
				<label for="" class="sr-only">Телефон</label>
				<input type="text" name="USER[PERSONAL_PHONE]" id="" class="form-control" value="<?=$arResult["USER"]["PERSONAL_PHONE"]?>" placeholder="Телефон" data-type="masked-phone-input">
			</div>
            <button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
                <span class="default-state init-state">Сохранить изменения</span><span class="done-state">
                <span data-icon="icon-msbutton-checkmark"></span>Изменения сохранены</span>
                <span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
                <span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span>
            </button>
            		  
        </form>
	</div>
</section>
<section class="user-passport">
	<div class="block-header">
		<h3 class="block-title">Паспортные данные</h3>
	</div>
	<div class="block-body">
        <form action="<?= $templateFolder ?>/ajax.php" class="user-passport-form" data-module="user-passport-form">
            <script type="text/x-config">
				{
					"dateMask": "99/99/9999",
					"passportSerialMask": "99 99",
					"passportNumberMask": "999999",
					"passportCodeMask": "999-999"
				}
			</script>
            <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
            <input type="hidden" name="action" value="passport"/>
            <?=bitrix_sessid_post()?>
			<div class="flex-row passport-number-row">
				<div class="form-group">
					<label for="" class="sr-only">Серия паспорта</label>
					<input type="text" name="USER[PASSPORT][SERIA]" id="" class="form-control" placeholder="Серия" value="<?=$arResult["USER"]["PASSPORT"]["SERIA"]?>" data-type="masked-passport-serial-input">
				</div>
				<div class="form-group">
					<label for="" class="sr-only">Номер паспорта</label>
					<input type="text" name="USER[PASSPORT][NUMBER]" id="" class="form-control" placeholder="Номер" value="<?=$arResult["USER"]["PASSPORT"]["NUMBER"]?>" data-type="masked-passport-number-input">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="sr-only">Кем выдан паспорт</label>
				<textarea name="USER[PASSPORT][WHO_ISSUED]" id="" rows="4" class="form-control" placeholder="Кем выдан"><?=$arResult["USER"]["PASSPORT"]["PREVIEW_TEXT"]?></textarea>
			</div>
			<div class="flex-row passport-additional-data-row">
				<div class="form-group has-feedback">
					<label for="" class="sr-only">Дата выдачи</label>
					<input type="text" name="USER[PASSPORT][WHEN_ISSUED]" id="" class="form-control" placeholder="Когда выдан" value="<?=$arResult["USER"]["PASSPORT"]["PROPERTY_WHEN_ISSUED_VALUE"]?>" data-type="masked-date-input">
				</div>
				<div class="form-group">
					<label for="" class="sr-only">Код подразделения</label>
					<input type="text" name="USER[PASSPORT][CODE_DIVISION]" id="" class="form-control" placeholder="Код подразделения" value="<?=$arResult["USER"]["PASSPORT"]["PROPERTY_CODE_DIVISION_VALUE"]?>" data-type="masked-passport-code-input">
				</div>
			</div>
			<div class="form-group">
				<label for="" class="sr-only">Адрес прописки</label>
				<textarea name="USER[PASSPORT][ADDRESS]" id="" rows="4" class="form-control" placeholder="Адрес прописки"><?=$arResult["USER"]["PASSPORT"]["DETAIL_TEXT"]?></textarea>
			</div>
			<button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button"><span class="default-state init-state">Сохранить данные</span><span class="done-state"><span data-icon="icon-msbutton-checkmark"></span>Данные сохранены</span><span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
                        <span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span></button>
		</form>
	</div>
</section>