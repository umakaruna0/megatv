<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("MegaTV");
global $USER;
?>

<?$APPLICATION->IncludeComponent("hawkart:user.records", "", Array("WATCHED"=>"N"), false);?>

<div class="fullsize-banner adv-styling-03">
	<div class="banner-content">
        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/records-banner-1.php"), false);?>
	</div>
</div>

<?/*
<section class="recomended-broadcasts is-single-items" data-module="recomended-broadcasts">
	<div class="block-header">
		<h3 class="block-title">Рекомендованные Передачи</h3>
	</div>
	<div class="block-body">
		<div class="broadcasts-list">
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-34.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Властелин колец: <br>Возвращение Короля</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-35.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Рим</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-36.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Гладиатор</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-37.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Тюдоры</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-38.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Борджиа</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-39.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Демоны Да Винчи.<br>2 серия 4 сезона.</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-34.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Властелин колец: <br>Возвращение Короля</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-35.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Рим</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-36.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Гладиатор</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-37.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Тюдоры</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-38.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Борджиа</a>
				</div>
			</div>
			<div class="item status-recordable" data-type="draggable" data-target="drop-area">
				<div class="item-image-holder" style="background-image: url(img/temp/image-39.jpg)"></div>
				<span class="item-status-icon">
					<span data-icon="icon-recordit"></span>
					<span class="status-desc">Записать</span>
				</span>
				<div class="item-header">
					<time>12:00 <span class="date">| 12.12.2015</span></time>
					<a href="#">Демоны Да Винчи.<br>2 серия 4 сезона.</a>
				</div>
			</div>
		</div>
	</div><!-- /.block-body -->
</section><!-- /.recomended-broadcasts -->
*/?>

<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/records-banner-2.php"), false);?>

<?$APPLICATION->IncludeComponent("hawkart:user.records", "", Array("WATCHED"=>"Y"), false);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>