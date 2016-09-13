<?
	$auth = true;
	require("include/header.php");
?>

<main class="site-content">
    <section class="section-h1 hidden">
        <h1></h1></section>
    <div class="flex-row user-profile-top-row">

        <section class="user-profile">
            <div class="block-header">
                <h3 class="block-title">Ваш профиль</h3>
            </div>
            <div class="block-body">
                <div class="avatar-col user-profile__avatar-col">
                    <div class="user-avatar-holder" data-module="avatar-loader">
                        <script type="text/x-config">
                            { "url": "/local/components/hawkart/user.profile/templates/.default/upload.php" }
                        </script>

                        <div class="progressbar-holder">
                            <input type="file" name="files[]" id="_id-avatar-loader--file" data-type="file-input">
                            <img src="/upload/main/d57/d5712afa0c05eba0fb272f7ead73e3ce.jpg" alt="">
                        </div>
                        <span class="load-avatar-text-holder load-avatar"><span data-icon="icon-replace-avatar" class="load-avatar__icon"></span><span class="load-avatar__title">Обновить</span></span>
                    </div>
                    <span class="user-name"></span>
                    <span class="user-city">
						<strong class="user-city__strong">Город: </strong>
						<span class="user-city__span">Москва</span>
                    </span>
                </div>

                <form action="/local/components/hawkart/user.profile/templates/.default/ajax.php" class="user-profile-form" data-module="user-profile-form">
                    <script type="text/x-config">
                        { "dateMask": "99/99/9999", "phoneMask": "+7 (999) 999-99-99" }
                    </script>
                    <input type="hidden" name="ajax_key" value="" />
                    <input type="hidden" name="action" value="profile" />
                    <input type="hidden" name="sessid" value="" />
                    <div class="form-group">
                        <label for="" class="sr-only">Ваше имя</label>
                        <input type="text" name="USER[NAME]" id="" class="form-control" value="" placeholder="Ваше имя">
                    </div>
                    <div class="form-group">
                        <label for="" class="sr-only">Ваша фамилия</label>
                        <input type="text" name="USER[LAST_NAME]" id="" class="form-control" value="" placeholder="Ваша фамилия">
                    </div>
                    <div class="form-group">
                        <label for="" class="sr-only">Ваше отчество</label>
                        <input type="text" name="USER[SECOND_NAME]" id="" class="form-control" value="" placeholder="Ваше отчество">
                    </div>
                    <div class="form-group has-feedback">
                        <label for="" class="sr-only">Дата рождения</label>
                        <input type="text" name="USER[PERSONAL_BIRTHDAY]" id="" class="form-control" value="" placeholder="Дата рождения" data-type="masked-birthdate-input">
                    </div>
                    <div class="form-group">
                        <label for="" class="sr-only">E-mail</label>
                        <input type="text" name="USER[EMAIL]" id="" class="form-control" value="geryh213921@gmail.com" placeholder="E-mail">
                    </div>
                    <div class="form-group">
                        <label for="" class="sr-only">Телефон</label>
                        <input type="text" name="USER[PERSONAL_PHONE]" id="" class="form-control" value="" placeholder="Телефон" data-type="masked-phone-input">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
                        <span class="default-state init-state">Сохранить изменения</span>
                        <span class="done-state"><span data-icon="icon-msbutton-checkmark"></span>Изменения сохранены</span>
                    <!-- Добавлено -->
                        <span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
                        <span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span>
                    <!-- Добавлено -->
                    </button>
                </form>
            </div>
        </section>
        <section class="user-passport">
            <div class="block-header">
                <h3 class="block-title">Паспортные данные</h3>
            </div>
            <div class="block-body">
                <form action="/local/components/hawkart/user.profile/templates/ajax.php" class="user-passport-form" data-module="user-passport-form">
                    <script type="text/x-config">
                        { "dateMask": "99/99/9999", "passportSerialMask": "99 99", "passportNumberMask": "999999", "passportCodeMask": "999-999" }
                    </script>
                    <input type="hidden" name="ajax_key" value="" />
                    <input type="hidden" name="action" value="passport" />
                    <input type="hidden" name="sessid" value="" />
                    <div class="flex-row passport-number-row">
                        <div class="form-group">
                            <label for="" class="sr-only">Серия паспорта</label>
                            <input type="text" name="USER[PASSPORT][SERIA]" id="" class="form-control" placeholder="Серия" value="" data-type="masked-passport-serial-input">
                        </div>
                        <div class="form-group">
                            <label for="" class="sr-only">Номер паспорта</label>
                            <input type="text" name="USER[PASSPORT][NUMBER]" id="" class="form-control" placeholder="Номер" value="" data-type="masked-passport-number-input">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="sr-only">Кем выдан паспорт</label>
                        <textarea name="USER[PASSPORT][WHO_ISSUED]" id="" rows="4" class="form-control" placeholder="Кем выдан"></textarea>
                    </div>
                    <div class="flex-row passport-additional-data-row">
                        <div class="form-group has-feedback">
                            <label for="" class="sr-only">Дата выдачи</label>
                            <input type="text" name="USER[PASSPORT][WHEN_ISSUED]" id="" class="form-control" placeholder="Когда выдан" value="" data-type="masked-date-input">
                        </div>
                        <div class="form-group">
                            <label for="" class="sr-only">Код подразделения</label>
                            <input type="text" name="USER[PASSPORT][CODE_DIVISION]" id="" class="form-control" placeholder="Код подразделения" value="" data-type="masked-passport-code-input">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="sr-only">Адрес прописки</label>
                        <textarea name="USER[PASSPORT][ADDRESS]" id="" rows="4" class="form-control" placeholder="Адрес прописки"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
                        <span class="default-state init-state">Сохранить данные</span>
                        <span class="done-state"><span data-icon="icon-msbutton-checkmark"></span>Данные сохранены</span>
                    <!-- Добавлено -->
                        <span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
                        <span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span>
                    <!-- Добавлено -->
                    </button>
                </form>
            </div>
        </section>
        <section class="user-info-subscriptions" data-module="user-info-subscriptions">
            <script type="text/x-config">
                { "url": "/server/" }
            </script>
            <div class="block-header">
                <h3 class="block-title">УПРАВЛЕНИЕ АНОНСАМИ И РЕКОМЕНДАЦИЯМИ</h3>
            </div>
            <div class="block-body">
                <ul class="user-info-subscriptions-list">
                    <li class="status-active" data-type="info-subscription-item" data-subscription-id="01">
                        <a href="#">Рекомендации МЕГА ТВ <span data-icon="icon-round-checkbox-mark"></span></a>
                    </li>
                    <li class="status-active" data-type="info-subscription-item" data-subscription-id="02">
                        <a href="#">Рекомендации Ваших друзей <span data-icon="icon-round-checkbox-mark"></span></a>
                    </li>
                    <li data-type="info-subscription-item" data-subscription-id="03">
                        <a href="#">Анонсы передач по СМС <span data-icon="icon-round-checkbox-mark"></span></a>
                    </li>
                    <li class="status-active" data-type="info-subscription-item" data-subscription-id="04">
                        <a href="#">Анонсы передач по е-mail <span data-icon="icon-round-checkbox-mark"></span></a>
                    </li>
                    <li class="status-active" data-type="info-subscription-item" data-subscription-id="05">
                        <a href="#">Уведомление о записанной передаче <span data-icon="icon-round-checkbox-mark"></span></a>
                    </li>
                    <li class="status-active" data-type="info-subscription-item" data-subscription-id="06">
                        <a href="#">Новости МЕГА ТВ <span data-icon="icon-round-checkbox-mark"></span></a>
                    </li>
                </ul>
            </div>
        </section>

    </div>
    <!-- /.user-profile-top-row -->
    <div class="flex-row user-profile-middle-row">

        <section class="user-attached-socials" data-module="user-attached-socials">
            <script type="text/x-config">
                { "popoverContent": "Привяжите свой аккаунт<br> и получите в подарок<br> +1 ГБ пространства" }
            </script>
            <div class="block-header">
                <h3 class="block-title">Привязка к соц. сетям</h3>
            </div>
            <div class="block-body">
                <p>Нажмите на соответствующую иконку соц. сети, чтобы связать ее с вашим аккаунтом:</p>
                <ul class="attached-socials-list">
                    <li>
                        <a href="/personal/social.php?provider=Google" data-type="popover-handler">
                            <span data-icon="icon-gp-social"></span>
                        </a>
                        <span class="decor">1 ГБ</span>
                    </li>
                    <li>
                        <a href="/personal/social.php?provider=Instagram" data-type="popover-handler">
                            <span data-icon="icon-im-social"></span>
                        </a>
                        <span class="decor">1 ГБ</span>
                    </li>
                    <li>
                        <a href="/personal/social.php?provider=Vkontakte" data-type="popover-handler">
                            <span data-icon="icon-vk-social"></span>
                        </a>
                        <span class="decor">1 ГБ</span>
                    </li>
                    <li>
                        <a href="/personal/social.php?provider=Facebook" data-type="popover-handler">
                            <span data-icon="icon-fb-social"></span>
                        </a>
                        <span class="decor">1 ГБ</span>
                    </li>
                    <li>
                        <a href="/personal/social.php?provider=LinkedIn" data-type="popover-handler">
                            <span data-icon="icon-in-social"></span>
                        </a>
                        <span class="decor">1 ГБ</span>
                    </li>
                    <li>
                        <a href="/personal/social.php?provider=Twitter" data-type="popover-handler">
                            <span data-icon="icon-tw-social"></span>
                        </a>
                        <span class="decor">1 ГБ</span>
                    </li>
                    <li>
                        <a href="/personal/social.php?provider=Yandex" data-type="popover-handler">
                            <span data-icon="icon-ya-social"></span>
                        </a>
                        <span class="decor">1 ГБ</span>
                    </li>
                    <li>
                        <a href="/personal/social.php?provider=Odnoklassniki" data-type="popover-handler">
                            <span data-icon="icon-ok-social"></span>
                        </a>
                        <span class="decor">1 ГБ</span>
                    </li>
                </ul>
            </div>
        </section>
        <section class="reset-password">
            <div class="block-header">
                <h3 class="block-title">Смена пароля</h3>
            </div>
            <div class="block-body">
                <form action="/local/components/hawkart/user.change_password/templates/.default/ajax.php" class="reset-password-form" data-module="reset-password-form">
                    <input type="hidden" name="ajax_key" value="76ce095374bbc723b7dde2bd46987d2c" />
                    <input type="hidden" name="sessid" id="sessid_4" value="2b35b4ee9717bf1eb6b73f087a056273" />
                    <div class="col">
                        <div class="form-group">
                            <label for="" class="sr-only">Старый пароль</label>
                            <input type="text" name="old-password" id="" class="form-control" placeholder="Старый пароль">
                        </div>
                        <div class="form-group">
                            <label for="" class="sr-only">Новый пароль еще раз</label>
                            <input type="text" name="new-password" id="" class="form-control" placeholder="Новый пароль еще раз">
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="" class="sr-only">Новый пароль</label>
                            <input type="text" name="new-password2" id="" class="form-control" placeholder="Новый пароль">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block btn-multistate" data-type="multistate-button">
                            <span class="default-state init-state">Сменить пароль</span>
                            <span class="done-state"><span data-icon="icon-msbutton-checkmark"></span>Пароль изменён</span>
                        <!-- Добавлено -->
                            <span class="fail-data-state"><span data-icon="icon-msbutton-cross-circle"></span>Проверьте введённые данные</span>
                            <span class="fail-network-state"><span data-icon="icon-msbutton-broken-network"></span>Ошибка соединения с сервером</span>
                        <!-- Добавлено -->
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <!-- /.user-profile-middle-row -->
</main>

<?
	$js = [
		"js/user-profile.js"
	];
	require("include/footer.php");
?>