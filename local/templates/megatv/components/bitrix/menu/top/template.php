<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!empty($arResult)):?>
<ul class="sections-menu">

<?
$arIcons = array("channels", "themes", "recommendations");
foreach($arResult as $key=>$arItem):
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
		<li class="active"><a href="<?=$arItem["LINK"]?>"><span data-icon="icon-<?=$arIcons[$key]?>"></span><span><?=$arItem["TEXT"]?></span></a></li>
	<?else:?>
		<li><a href="<?=$arItem["LINK"]?>"><span data-icon="icon-<?=$arIcons[$key]?>"></span><span><?=$arItem["TEXT"]?></span></a></li>
	<?endif?>
	
<?endforeach?>

</ul>
<?endif?>