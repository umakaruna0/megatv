<div class="comments-holder">
	<a href="#" class="comment-form-trigger-link" data-type="comment-form-trigger"><span data-icon="icon-paper-airplane"></span><span>Оставить отзыв</span></a>
	<div class="broadcast-user-comments-form">
		<div class="block-header">
			<h3 class="block-title">Оставить отзыв</h3>
		</div>
		<div class="block-body">
			<form action="<?= $templateFolder ?>/ajax.php" id="comment-form">
				<div class="form-group">
                    <input type="hidden" name="ajax_key" value="<?=md5('ajax_'.LICENSE_KEY)?>" />
                    <input type="hidden" name="prog_id" value="<?=$arParams["PROG_ID"]?>" />
                    <?=bitrix_sessid_post()?>
					<textarea name="text" id="" rows="3" class="form-control"></textarea>
					<button type="submit" class="submit-btn"><span data-icon="icon-comments-submit-arrow"></span></button>
				</div>
			</form>
		</div>
	</div>
	<div class="broadcast-user-comments">
		<div class="block-header">
			<h3 class="block-title">Отзывы</h3>
		</div>
		<div class="block-body">
			<ul class="comments-list">
                <?
                foreach($arResult["COMMENTS"] as $arComment)
                {
                    $arUser = $arResult["USERS"][$arComment["UF_USER_ID"]];
                    
                    $date = $arComment['UF_DATETIME']->toString();
                    $arDATE = ParseDateTime($date, FORMAT_DATETIME);
                    ?>
                    <li>
    					<div class="user-avatar<?if($arUser["PERSONAL_PHOTO"]):?> is-empty<?endif;?>">
    						<?if($arUser["PERSONAL_PHOTO"]):?>
                                <img src="<?=CFile::GetPath($arUser["PERSONAL_PHOTO"])?>" alt="<?=$USER->GetFullName()?>" width="50" height="50">
                            <?endif;?>
    					</div>
    					<div class="comment-holder">
    						<div class="comment-title"><?=trim($arUser["NAME"]." ".$arUser["LAST_NAME"])?> | <?=$arDATE["DD"]." ".ToLower(GetMessage("MONTH_".intval($arDATE["MM"])."_S"))." ".$arDATE["YYYY"];?></div>
    						<div class="comment-text"><p><?=$arComment["UF_TEXT"]?></p></div>
    					</div>
    				</li>
                    <?
                }
                ?>
			</ul>
		</div>
	</div>
</div>