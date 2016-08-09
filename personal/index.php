<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Персональные данные");
global $USER;
?>

<div class="flex-row user-profile-top-row">

    <?$APPLICATION->IncludeComponent("hawkart:user.profile", "", Array("CITY_GEO"=>\Hawkart\Megatv\CityTable::getGeoCity()), false);?>
    
	<section class="user-info-subscriptions" data-module="user-info-subscriptions">
		<script type="text/x-config">
			{
				"url": "/server/"
			}
		</script>
		<div class="block-header">
			<h3 class="block-title">УПРАВЛЕНИЕ АНОНСАМИ И РЕКОМЕНДАЦИЯМИ</h3>
		</div>
		<div class="block-body">
			<ul class="user-info-subscriptions-list">
				<li class="status-active" data-type="info-subscription-item" data-subscription-id="01">
					<a href="#">Рекомендации МЕГА ТВ <span data-icon="icon-round-checkbox-mark"></span></a>
				</li>
				<li class="status-active" data-type="info-subscription-item" data-subscription-id="02">
					<a href="#">Рекомендации Ваших друзей <span data-icon="icon-round-checkbox-mark"></span></a>
				</li>
				<li data-type="info-subscription-item" data-subscription-id="03">
					<a href="#">Анонсы передач по СМС <span data-icon="icon-round-checkbox-mark"></span></a>
				</li>
				<li class="status-active" data-type="info-subscription-item" data-subscription-id="04">
					<a href="#">Анонсы передач по е-mail <span data-icon="icon-round-checkbox-mark"></span></a>
				</li>
				<li class="status-active" data-type="info-subscription-item" data-subscription-id="05">
					<a href="#">Уведомление о записанной передаче <span data-icon="icon-round-checkbox-mark"></span></a>
				</li>
				<li class="status-active" data-type="info-subscription-item" data-subscription-id="06">
					<a href="#">Новости МЕГА ТВ <span data-icon="icon-round-checkbox-mark"></span></a>
				</li>
			</ul>
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