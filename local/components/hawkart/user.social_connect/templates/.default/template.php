<section class="user-attached-socials"  data-module="user-attached-socials">
    <script type="text/x-config">
		{
			"popoverContent": "Привяжите свой аккаунт<br> и получите в подарок<br> +1 ГБ пространства"
		}
	</script>
	<div class="block-header">
		<h3 class="block-title">Привязка к соц. сетям</h3>
	</div>
	<div class="block-body">
		<p>Нажмите на соответствующую иконку соц. сети, чтобы связать ее с вашим аккаунтом:</p>
		<ul class="attached-socials-list">
            <?
            foreach($arResult["SOCIALS"] as $arSocial)
            {
                ?>
                <li <?if($arSocial["CHECKED"]):?>class="active"<?endif;?>>
					<a href="<?if(!$arSocial["CHECKED"]):?>/personal/social.php?provider=<?=$arSocial["PROVIDER"]?><?else:?>#<?endif;?>" data-type="popover-handler">
						<span data-icon="icon-<?=$arSocial["ICON"]?>-social"></span>
					</a>
					<span class="decor">1 ГБ</span>
				</li>
                <?
            }
            ?>
		</ul>
	</div>
</section>