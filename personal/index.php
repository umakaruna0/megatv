<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("MegaTV");
global $USER;
?>

<style>
.form-group.has-error .form-control
{
    padding-top: 0;
    padding-bottom: 20px;
}
</style>

<div class="flex-row user-profile-top-row">

    <?$APPLICATION->IncludeComponent("hawkart:user.profile", "", Array(), false);?>
    
	<section class="user-info-subscriptions">
		<div class="block-header">
			<h3 class="block-title">УПРАВЛЕНИЕ АНОНСАМИ И РЕКОМЕНДАЦИЯМИ</h3>
		</div>
		<div class="block-body">
			<form action="#" class="user-info-subscriptions-form">
				<div class="checkbox">
					<label for="_id-user-info-subscriptions-form-checkbox-01"><span>Рекомендации МЕГА ТВ</span><input type="checkbox" name="user-info-subscriptions-form-checkbox-01" id="_id-user-info-subscriptions-form-checkbox-01" checked><span class="checkbox-replace"><span data-icon="icon-round-checkbox-mark"></span></span></label>
				</div>
				<div class="checkbox">
					<label for="_id-user-info-subscriptions-form-checkbox-02"><span>Рекомендации Ваших друзей</span><input type="checkbox" name="user-info-subscriptions-form-checkbox-02" id="_id-user-info-subscriptions-form-checkbox-02" checked><span class="checkbox-replace"><span data-icon="icon-round-checkbox-mark"></span></span></label>
				</div>
				<div class="checkbox">
					<label for="_id-user-info-subscriptions-form-checkbox-03"><span>Анонсы передач по СМС</span><input type="checkbox" name="user-info-subscriptions-form-checkbox-03" id="_id-user-info-subscriptions-form-checkbox-03"><span class="checkbox-replace"><span data-icon="icon-round-checkbox-mark"></span></span></label>
				</div>
				<div class="checkbox">
					<label for="_id-user-info-subscriptions-form-checkbox-04"><span>Анонсы передач по е-mail</span><input type="checkbox" name="user-info-subscriptions-form-checkbox-04" id="_id-user-info-subscriptions-form-checkbox-04" checked><span class="checkbox-replace"><span data-icon="icon-round-checkbox-mark"></span></span></label>
				</div>
				<div class="checkbox">
					<label for="_id-user-info-subscriptions-form-checkbox-05"><span>Уведомление о записанной передаче</span><input type="checkbox" name="user-info-subscriptions-form-checkbox-05" id="_id-user-info-subscriptions-form-checkbox-05" checked><span class="checkbox-replace"><span data-icon="icon-round-checkbox-mark"></span></span></label>
				</div>
				<div class="checkbox">
					<label for="_id-user-info-subscriptions-form-checkbox-06"><span>Новости МЕГА ТВ</span><input type="checkbox" name="user-info-subscriptions-form-checkbox-06" id="_id-user-info-subscriptions-form-checkbox-06" checked><span class="checkbox-replace"><span data-icon="icon-round-checkbox-mark"></span></span></label>
				</div>
			</form>
		</div>
	</section>
    
</div><!-- /.user-profile-top-row -->
<div class="flex-row user-profile-middle-row">

    <?$APPLICATION->IncludeComponent("hawkart:user.social_connect", "", Array(), false);?>
    
    <?$APPLICATION->IncludeComponent("hawkart:user.change_password", "", Array(), false);?>
</div><!-- /.user-profile-middle-row -->

<?/*<div class="user-friends-latest-comments">
	<div class="block-header">
		<h3 class="block-title">Последние комментарии</h3>
	</div>
	<div class="block-body">
		<ul class="users-comments-list">
			<li>
				<span class="user-avatar"><img src="img/temp/user-avatar-08.jpg" alt="" width="70" height="70"></span>
				<span class="user-name">Пушкин<br>Александр</span>
			</li>
			<li>
				<span class="user-avatar"><img src="img/temp/user-avatar-09.jpg" alt="" width="70" height="70"></span>
				<span class="user-name">Лермонтов<br>Михаил</span>
			</li>
			<li>
				<span class="user-avatar"><img src="img/temp/user-avatar-10.jpg" alt="" width="70" height="70"></span>
				<span class="user-name">Гала</span>
			</li>
			<li>
				<span class="user-avatar"><img src="img/temp/user-avatar-11.jpg" alt="" width="70" height="70"></span>
				<span class="user-name">Гала</span>
			</li>
			<li>
				<span class="user-avatar"><img src="img/temp/user-avatar-12.jpg" alt="" width="70" height="70"></span>
				<span class="user-name">Гала</span>
			</li>
			<li>
				<span class="user-avatar"><img src="img/temp/user-avatar-13.jpg" alt="" width="70" height="70"></span>
				<span class="user-name">Лермонтов<br>Михаил</span>
			</li>
			<li>
				<span class="user-avatar"><img src="img/temp/user-avatar-14.jpg" alt="" width="70" height="70"></span>
				<span class="user-name">Толстой<br>Алексей</span>
			</li>
			<li>
				<span class="user-avatar"><img src="img/temp/user-avatar-15.jpg" alt="" width="70" height="70"></span>
				<span class="user-name">Толстой<br>Лев</span>
			</li>
			<li>
				<span class="user-avatar"><img src="img/temp/user-avatar-16.jpg" alt="" width="70" height="70"></span>
				<span class="user-name">Портная<br>Ирина</span>
			</li>
		</ul>
	</div>
</div>*/?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>