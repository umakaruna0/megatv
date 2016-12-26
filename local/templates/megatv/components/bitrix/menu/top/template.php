<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (!empty($arResult)):?>
<div class="box-right__box-menu">
<?
$arIcons = array("channels", "recommendations", /*"icon-film-collection",*/ "channels");
foreach($arResult as $key=>$arItem):
    if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
        continue;

    if($arItem["LINK"]=="/personal/records/")
    {
        $showMyRecs = true;
        $myRecSelected = $arItem;
        continue;
    }
    
    if($arItem["SELECTED"]):?>
        <a class="box-menu__link menu-link menu-link--active" href="<?=$arItem["LINK"]?>"><span data-icon="icon-<?=$arIcons[$key]?>" class="menu-link__icon g-icon"></span><span class="box-menu__title"><?=$arItem["TEXT"]?></span></a>
    <?else:?>
        <a class="box-menu__link menu-link" href="<?=$arItem["LINK"]?>"><span data-icon="icon-<?=$arIcons[$key]?>" class="menu-link__icon g-icon"></span><span class="box-menu__title"><?=$arItem["TEXT"]?></span></a>
    <?endif?>
    
<?endforeach?>
    <?if($showMyRecs):?>
        <a class="box-menu__link menu-link<?if($arItem["SELECTED"]):?> menu-link--active<?endif;?> item-recording" href="/personal/records/">
            <span data-icon="icon-film-collection" class="menu-link__icon g-icon"></span>
            <span class="box-menu__title" style="text-align: right;">
                <span class="item-recording__count"><?=$APPLICATION->GetPageProperty("ar_record_in_rec")?> <?=GetMessage('FROM')?> <?=$APPLICATION->GetPageProperty("ar_record_total")?></span> <span onclick="window.location.href='/personal/records/';"><?=$myRecSelected["TEXT"]?></span>
                <span class="item-empty-space__count">Свободно: <?=$APPLICATION->ShowViewContent("user_empty_space")?> Гб</span>
            </span>
        </a>
    <?endif;?>
</div>
<?endif?>