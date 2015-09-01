<nav class="header-nav" data-module="user-navigation">
	<ul class="user-actions">
        <?
        if($USER->IsAuthorized())
        {
            $arFilter = array(
                "UF_URL" => false,
                "UF_USER" => $USER->GetID()
            );
            $records_in = CRecordEx::getList($arFilter, array("UF_SOTAL_ID"));
            $countInRec = intval(count($records_in));
            
            $arFilter = array(
                "!UF_URL" => false,
                "UF_USER" => $USER->GetID()
            );
            $recorded = CRecordEx::getList($arFilter, array("UF_SOTAL_ID"));
            $countRecorded = intval(count($recorded));
            
            $budget = floatval(CUserEx::getBudget());
            $arUser = CUserEx::OnAfterUserUpdateHandler($USER->GetID());
            $APPLICATION->AddViewContent('user_budget', number_format($budget, 0, "", " "));
            ?>
            <div class="user-card">
				<a href="/personal/" class="user-avatar<?if($arUser["PERSONAL_PHOTO"]):?> is-empty<?endif;?>" data-type="avatar-holder">
                    <?if($arUser["PERSONAL_PHOTO"]):?>
                        <img src="<?=CFile::GetPath($arUser["PERSONAL_PHOTO"])?>" alt="<?=$USER->GetFullName()?>" width="50" height="50">
                    <?endif;?>
                </a>
				<div class="info-panel">
					<a class="username" href="/personal/"><?=$USER->GetFullName()?></a><br>
					<a href="<?=$APPLICATION->GetCurDir()?>?logout=yes" class="signout-link">Выйти</a>
				</div>
			</div>
            
            <ul class="top-menu">
				<li><a href="/personal/records/"><span data-icon="icon-film-collection"></span> Мои записи</a></li>
				<li><a href="#"><span data-icon="icon-recording-small"></span> В записи <span class="badge" data-type="recording-count"><?=$countInRec?></span></a></li>
				<li><a href="#"><span data-icon="icon-recorded-small"></span> Записанных <span class="badge"><?=$countRecorded?></span></a></li>
				<li><a href="/personal/services/"><span data-icon="icon-balance" data-size="small"></span> На счету: <?=number_format($budget, 0, "", " ")?> Р</a></li>
			</ul>
            <?
        }else{
            ?>
            <li><a href="#" class="signin-link" data-type="auth-screens-trigger" data-target="#singin-form">Войти</a></li>
            <li><a href="#" class="signup-link" data-type="auth-screens-trigger" data-target="#singup-form">Зарегистрироваться</a></li>
            <?
        }
        ?>
	</ul>
</nav>