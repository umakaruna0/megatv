<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();
CModule::IncludeModule("iblock");
CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");

global $USER;
if(!is_object($USER))
    $USER = new CUser;
    
$broadcastID = intval($_GET["broadcastID"]);

if($_GET["record"])
{
    $arRecord = CRecordEx::getByID($broadcastID);
}else{
    $arRecords = CRecordEx::getList(array("UF_USER"=> $USER->GetID(), "UF_SCHEDULE"=>$broadcastID), array("UF_PROG", "ID", "UF_URL"));
    $arRecord = $arRecords[0];
}

$arProg = CProg::getByID($arRecord["UF_PROG"], array("NAME", "PROPERTY_SUB_TITLE", "PROPERTY_PICTURE_DOUBLE", "ID"));
$arProg["PICTURE"] = CDev::resizeImage($arProg["PROPERTY_PICTURE_DOUBLE_VALUE"], 896, 504);

$arWatched = CRecordEx::getList(array("!UF_WATCHED"=> false, "UF_PROG"=>$arProg["ID"]), array("ID"));
?>
<div class="advert-holder">
    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/player-banner.php"), false);?>
</div>
<div class="broadcast-player" data-module="broadcast-player">
	<script type="text/x-config">
		{
			"seekTime": "<?=intval($arRecord["UF_PROGRESS_SECS"])?>",
			"broadcastID": "<?=$broadcastID?>",
			"streamURL": "<?=$arRecord["UF_URL"]?>",
			"posterURL": "<?=$arProg["PICTURE"]["SRC"]?>",
			"videoTitle": "<?=$arProg["NAME"]?><?= $arProg["PROPERTY_SUB_TITLE_VALUE"] ? " | ".$arProg["PROPERTY_SUB_TITLE_VALUE"] : "" ?>",
            "playerFlashURL": "<?=SITE_TEMPLATE_PATH?>/megatv/app/js/vendors/jwplayer/jwplayer.flash.swf"
		}
	</script>
	<div class="block-header">
		<h3 class="block-title"><?=$arProg["NAME"]?><?= $arProg["PROPERTY_SUB_TITLE_VALUE"] ? " <small>|".$arProg["PROPERTY_SUB_TITLE_VALUE"]."</small>" : "" ?></h3>
	</div>
	<div class="block-body">
		<a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
		<div class="player-holder">
			<div id="player"></div>
			<div class="player-panel">
				<dl class="view-count">
					<dt>Просмотров:</dt>
					<dd><?=intval(count($arWatched))?></dd>
				</dl>
				<?/*<dl class="download-count">
					<dt>Скачиваний:</dt>
					<dd>9 157 000</dd>
				</dl>
				<div class="social-share">
					<span>Рассказать друзьям:</span>
					<ul class="socuals-list">
						<li><a href="#"><span data-icon="icon-vk-social"></span></a></li>
						<li><a href="#"><span data-icon="icon-fb-social"></span></a></li>
						<li><a href="#"><span data-icon="icon-tw-social"></span></a></li>
						<li><a href="#"><span data-icon="icon-gp-social"></span></a></li>
					</ul>
				</div>
				<a href="broadcast-card-recommendate.html" class="btn btn-default"><span data-icon="icon-network-social"></span>Рекомендовать друзьям</a>*/?>
			</div>
		</div>
        <?/*
		<div class="comments-holder">
			<a href="#" class="comment-form-trigger-link" data-type="comment-form-trigger"><span data-icon="icon-paper-airplane"></span><span>Оставить отзыв</span></a>
			<div class="broadcast-user-comments-form">
				<div class="block-header">
					<h3 class="block-title">Оставить отзыв</h3>
				</div>
				<div class="block-body">
					<form action="#">
						<div class="form-group">
							<textarea name="" id="" rows="3" class="form-control"></textarea>
							<button type="submit" class="submit-btn"><span data-icon="icon-comments-submit-arrow"></span></button>
						</div>
					</form>
				</div>
			</div>
			<div class="broadcast-user-comments">
				<div class="block-header">
					<h3 class="block-title">Отзывы друзей</h3>
				</div>
				<div class="block-body">
					<ul class="comments-list">
						<li>
							<div class="user-avatar">
								<img src="img/temp/user-avatar-02.jpg" alt="" width="50" height="50">
							</div>
							<div class="comment-holder">
								<div class="comment-title">Марк Уолберг | 5 минут назад</div>
								<div class="comment-text"><p>Кино по мне. Романтика, драма. Жаль я не успел на этот проект</p></div>
							</div>
						</li>
						<li>
							<div class="user-avatar">
								<img src="img/temp/user-avatar-03.jpg" alt="" width="50" height="50">
							</div>
							<div class="comment-holder">
								<div class="comment-title">Аманда Сайфред | 23 апреля 2015</div>
								<div class="comment-text"><p>Если бы да ко бы. Но фильм хорош.</p></div>
							</div>
						</li>
						<li>
							<div class="user-avatar">
								<img src="img/temp/user-avatar-04.jpg" alt="" width="50" height="50">
							</div>
							<div class="comment-holder">
								<div class="comment-title">Сет МакФарлейн | 23 апреля 2015</div>
								<div class="comment-text"><p>Doctor: “I’m afraid it’s cancer.” Patient: “Yeah, I’m gonna need to get a second opinion from Jim Carrey."</p></div>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
        */?>
	</div>
</div><!-- .broadcast-player -->
<?die();?>