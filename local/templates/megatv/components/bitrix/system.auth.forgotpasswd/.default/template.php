
<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/**
 * Bitrix vars
 *
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $arParams
 * @var array $arResult
 * @var array $arLangMessages
 * @var array $templateData
 *
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $parentTemplateFolder
 * @var string $templateName
 * @var string $componentPath
 *
 * @var CDatabase $DB
 * @var CUser $USER
 * @var CMain $APPLICATION
 */
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(TRUE);
?>

<?
//ShowMessage($arParams["~AUTH_RESULT"]);
?>

<div class="authorize-overlay is-reset-overlay" data-module="reset-overlay">
	<div class="overlay-content">
		<h4 class="overlay-title">Восстановить пароль</h4>
		<div class="steps">
			<div class="step fade in active">
        		<p>Напишите свой телефон или адрес эл. почты, и через 30 сек. мы вышлем новый.</p>
        		<form action="<?= $templateFolder ?>/ajax.php">
                    <?if (strlen($arResult["BACKURL"]) > 0):?>
                		<input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                	<?endif;?>
                	<input type="hidden" name="TYPE" value="SEND_PWD">
                    
                    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                    <?=bitrix_sessid_post()?>
                    
        			<div class="form-group">
        				<label for="" class="sr-only">Телефон или эл. почта</label>
        				<input type="text" name="USER_EMAIL" id="" class="form-control" placeholder="Телефон или эл. почта" data-type="adaptive-field">
        			</div>
        			<div class="form-actions">
        				<button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
        					<span class="default-state init-state">Выслать пароль</span>
        					<span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
        					<span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span>
        				</button>
        				<a href="#" class="form-subaction-link" data-type="signin-handler-link">Я вспомнил пароль</a><span class="inline-divider">|</span><a href="#" class="form-subaction-link" data-type="reset-code-handler-link">У меня уже есть код</a>
        			</div>
        		</form>
            </div>
            <div class="step fade">
				<form action="<?= $templateFolder ?>/ajax_change_password.php" data-type="reset-code-form">
                    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                    <input type="hidden" name="USER_EMAIL" value="" data-type="adaptive-field-hidden" />
                    <?=bitrix_sessid_post()?>
                    
					<div class="form-group">
						<label for="" class="sr-only">Код для смены пароля</label>
						<input type="text" name="checkword" id="" class="form-control" placeholder="Код для смены пароля" data-type="code-field">
					</div>
					<div class="form-group has-feedback" data-type="password-field-group">
						<label for="" class="sr-only">Новый пароль</label>
						<input type="password" name="password" id="" class="form-control" placeholder="Новый пароль" data-type="password-field">
						<input type="text" class="form-control" data-type="password-visualizer" placeholder="Новый пароль">
						<span class="form-control-feedback">
							<a href="#" data-type="password-visibility-toggle"><span data-icon="icon-password-eye"></span></a>
						</span>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
							<span class="default-state init-state">Изменить пароль</span>
							<span class="done-state">Запрос отправлен</span>
							<span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
							<span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span>
						</button>
						<a href="#" class="form-subaction-link" data-type="signin-handler-link">Я вспомнил пароль</a><span class="inline-divider">|</span><a href="#" class="form-subaction-link" data-type="reset-handler-link">Запросить код ещё раз</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>