<section class="user-attached-socials">
	<div class="block-header">
		<h3 class="block-title">Привязка к соц. сетям</h3>
	</div>
	<div class="block-body">
		<p>Нажмите на соответствующую иконку соц. сети, чтобы связать ее с вашим аккаунтом:</p>
		<ul class="attached-socials-list">
            <?
            //CDev::pre($arResult["SOCIALS"]);
            foreach($arResult["SOCIALS"] as $arSocial)
            {
                ?>
                <li <?if($arSocial["CHECKED"]):?>class="active"<?endif;?>>
                    <a href="<?if(!$arSocial["CHECKED"]):?>/personal/social.php?provider=<?=$arSocial["PROVIDER"]?><?else:?>#<?endif;?>" data-holder-size="<?=$arSocial["GB"]?> ГБ">
                    <span data-icon="icon-<?=$arSocial["ICON"]?>-social"></span>
                    <?//if(!$arSocial["CHECKED"]):?></a><?//endif;?>
                </li>
                <?
            }
            ?>
		</ul>
	</div>
</section>