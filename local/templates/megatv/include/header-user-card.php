<nav class="header-nav">
	<ul class="user-actions">
        <?
        if($USER->IsAuthorized())
        {
            $arUser = CUserEx::OnAfterUserUpdateHandler($USER->GetID());
            ?>
            <div class="user-card">
				<a href="/personal/" class="user-avatar">
                    <?if($arUser["PERSONAL_PHOTO"]):?>
                        <img src="<?=CFile::GetPath($arUser["PERSONAL_PHOTO"])?>" alt="<?=$USER->GetFullName()?>" width="50" height="50">
                    <?else:?>
					    <img src="<?=SITE_TEMPLATE_PATH?>/img/temp/user-avatar-01.jpg" alt="<?=$USER->GetFullName()?>" width="50" height="50">
                    <?endif;?>
                </a>
				<div class="info-panel">
					<a class="username" href="/personal/"><?=$USER->GetFullName()?></a><br>
					<a href="<?=$APPLICATION->GetCurDir()?>?logout=yes" class="signout-link">Выйти</a>
				</div>
			</div>
            
            <ul class="top-menu">
				<li><a href="/personal/records/"><span data-icon="icon-film-collection"></span> Мои записи</a></li>
				<li><a href="#"><span data-icon="icon-recording-small"></span> В записи <span class="badge">12</span></a></li>
				<li><a href="#"><span data-icon="icon-recorded-small"></span> Записанных <span class="badge">92</span></a></li>
				<li><a href="/personal/services/"><span data-icon="icon-balance" data-size="small"></span> На счету: 1 200 Р</a></li>
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