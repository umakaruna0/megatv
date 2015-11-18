<?
if(count($arResult["RECORDS"])==0)
    return false;
?>

<section class="user-recorded-broadcasts<?if(count($arResult["RECORDS"])<20):?> is-single-line<?endif;?>" data-module="user-recorded-broadcasts">
    <script type="text/x-config">
		{
			"url": "<?=SITE_TEMPLATE_PATH?>/ajax/delete_record.php"
		}
	</script>
	<div class="block-header">
		<h3 class="block-title"><?=$arParams["WATCHED"]=="Y" ? "Просмотренные" : "Мои записи" ?> <small>| <?=count($arResult["RECORDS"])?> <?=CDev::number_ending(count($arResult["RECORDS"]), "передач", "передача", "передачи")?></small></h3>
	</div>
	<div class="block-body">
		<div class="broadcasts-list">
			<div class="row-wrap">
                <div class="items-row">
                <?
                $count = 0;
                $half = ceil(count($arResult["RECORDS"])/2);    //10
                foreach($arResult["RECORDS"] as $arRecord)
                {
                    if(count($arResult["RECORDS"])>=20 && $count%$half==0 && $count!=0)
                    {
                        ?></div><div class="items-row"><?
                    }
                    ?>
                    <div class="item" data-broadcast-id="<?=$arRecord["ID"]?>">
                        <?
                        if($arParams["WATCHED"]=="Y")
                        {
                            $path = $_SERVER["DOCUMENT_ROOT"].$arRecord["PICTURE"]["SRC"];
                            ?><div class="item-image-holder" style="background-image: url(<?=SITE_TEMPLATE_PATH?>/ajax/img_grey.php?path=<?=urlencode($path)?>)"></div><?
                        }else{
                            $img = $arRecord["PICTURE"]["SRC"];
                            ?><div class="item-image-holder" style="background-image: url(<?=$img?>)"></div><?
                        }
                        ?>
						
						<div class="actions-panel">
							<ul class="actions-list">
								<li><a href="#" data-type="delete-trigger"><span data-icon="icon-trash-action"></span></a></li>
								<?/*<li><a href="#" data-type="share-trigger"><span data-icon="icon-network-action"></span></a></li>*/?>
								<?if(!empty($arRecord["UF_URL"])):?>
                                    <li><a href="#" data-type="player-trigger"><span data-icon="icon-play-action"></span></a></li>
                                <?endif;?>
							</ul>
							<div class="delete-dialog">
								<p>Вы уверены, что хотите удалить данную передачу навсегда?</p>
								<ul>
									<li><a href="#" data-type="delete-broadcast">Да, хочу</a></li>
									<li><a href="#" data-type="cancel-delete-state">Отменить</a></li>
								</ul>
							</div>
						</div>
						<div class="item-header">
							<div class="view-progress" data-progress="<?=intval($arRecord["UF_PROGRESS_PERS"])?>"></div>
							<span><?=$arRecord["UF_NAME"]?></span>
						</div>
					</div>
                    <?
                    $count++;
                }
                ?>
				</div><!-- /.items-row -->
			</div>
		</div><!-- /.broadcasts-list -->
	</div>
</section>