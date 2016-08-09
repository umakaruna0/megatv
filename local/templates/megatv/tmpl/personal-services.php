<?
    $auth = true;
    require("include/header.php");
?>

<main class="site-content">
    <section class="section-h1 hidden">
        <h1></h1></section>
    <div class="flex-row user-services-top-row">

        <section class="subscription-services" data-module="subscription-services">
            <script type="text/x-config">
                { "filledDiskSpace" : "0.198", "url": "/local/components/hawkart/subscription.services/templates/.default/ajax.php" }
            </script>
            <div class="block-header">
                <h3 class="block-title">Ваши услуги</h3>
            </div>
            <div class="block-body">
                <div class="storage-statistic">
                    <span class="total-space">Всего <strong>95 ГБ</strong></span>
                    <span class="used-space">Занято <strong>18.81 ГБ</strong></span>
                    <div class="progressbar-holder"></div>
                </div>
                <ul class="available-subscriptions">
                    <li class="item" data-service-id="1" data-type="service-item">
                        <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                        <div class="subscription-text-logo">+10 ГБ</div>

                        <div class="item-header">
                            <span class="price">2 Р <small>сутки</small></span>
                            <span class="item-title">Добавить<br> + 10 ГБ памяти</span>
                        </div>
                    </li>
                    <li class="item" data-service-id="2" data-type="service-item">
                        <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                        <div class="subscription-logo">
                            <span data-icon="icon-yadisk-service"></span>
                        </div>

                        <div class="item-header">
                            <span class="price">1 Р <small>сутки</small></span>
                            <span class="item-title">Сохранение <br>на Яндекс.Диск</span>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        <section class="user-balance" data-module="user-balance">

            <script type="text/x-config">
                { "errors": { "incorrect_val_amount": "Некорректное значение для поля с суммой!", "incorrect_paymethod": "Вы не выбрали метод оплаты!" } }
            </script>

            <div class="modal fade paymethod-modal" id="paymethod-modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Пополнение баланса</h3>
                            <div class="paymethod-modal-balance"><span data-icon="icon-balance" data-size="small"></span> На счету: 11512 <span data-icon="icon-ruble"></span></div>
                        </div>
                        <a href="#" onmouseup="$(document.body).removeClass('payment-opened')" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>

                        <form method="post" action="/local/templates/megatv/ajax/get_payment.php" class="asd-prepaid-form">

                            <input type="hidden" name="prepaid_money" value="Y" />
                            <input type="hidden" id="bx-asd-baseformat" value="# руб." />
                            <input type="hidden" id="bx-asd-comission" value="0" />
                            <input type="hidden" name="account" id="bx-asd-account" value="RUB" data-factor="1" />
                            <input type="hidden" name="sessid" id="sessid_2" value="164ec3685094e0a7ca07c7d6d2980039" />
                            <div class="form-group has-feedback">
                                <label for="_id-paymethod--summ">Введите сумму для зачисления: </label>
                                <input type="text" name="amount" id="bx-asd-amount" value="" class="form-control" data-type="paymethod-field">
                                <span class="form-control-feedback"><span data-icon="icon-ruble"></span></span>
                            </div>
                            <div class="radio-group">
                                <label for="">Выберите способ платежа:</label>

                                <div class="radio">
                                    <label for="asd_ps_1">
                                        <input type="radio" name="pay_system" id="asd_ps_1" value="1">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-promsvasbank-paymethod"></span>
                                        <span class="radio-text">Промсвязьбанк</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_2">
                                        <input type="radio" name="pay_system" id="asd_ps_2" value="2">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-cards-paymethod"></span>
                                        <span class="radio-text">Банковские карты</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_3">
                                        <input type="radio" name="pay_system" id="asd_ps_3" value="3">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-megafon-paymethod"></span>
                                        <span class="radio-text">МегаФон</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_4">
                                        <input type="radio" name="pay_system" id="asd_ps_4" value="4">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-mts-paymethod"></span>
                                        <span class="radio-text">МТС</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_5">
                                        <input type="radio" name="pay_system" id="asd_ps_5" value="5">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-beeline-paymethod"></span>
                                        <span class="radio-text">Билайн</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_6">
                                        <input type="radio" name="pay_system" id="asd_ps_6" value="6">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-yandexmoney-paymethod"></span>
                                        <span class="radio-text">Яндекс.Деньги</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_7">
                                        <input type="radio" name="pay_system" id="asd_ps_7" value="7">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-webmoney-paymethod"></span>
                                        <span class="radio-text">WebMoney</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_8">
                                        <input type="radio" name="pay_system" id="asd_ps_8" value="8">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-sberbankonline-paymethod"></span>
                                        <span class="radio-text">Сбербанк Онлайн</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_9">
                                        <input type="radio" name="pay_system" id="asd_ps_9" value="9">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-alfaclick-paymethod"></span>
                                        <span class="radio-text">Альфа-Клик</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_10">
                                        <input type="radio" name="pay_system" id="asd_ps_10" value="10">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon-masterpass-paymethod"></span>
                                        <span class="radio-text">MasterPass</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label for="asd_ps_11">
                                        <input type="radio" name="pay_system" id="asd_ps_11" value="11">
                                        <span class="overlap-bg"></span>
                                        <span class="decor">
    								<span data-icon="icon-round-checkbox-mark"></span>
                                        </span>
                                        <span data-icon="icon--paymethod"></span>
                                        <span class="radio-text">Внутренний счёт</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-block" data-type="paymethod-submit" disabled>Пополнить счет</button>
                            </div>
                        </form>
                        <div id="form-pay-request" style="display: none;"></div>
                    </div>
                </div>
            </div>
            <div class="block-header">
                <h3 class="block-title">Баланс</h3>
            </div>
            <div class="block-body">
                <div class="account-balance">
                    <span data-icon="icon-balance"></span><small>На счету:</small> 11 512 Р
                    <a href="#" class="btn btn-primary btn-block" data-type="paymethod-modal-handler">Пополнить счет</a>
                </div>

                <div class="balance-history">
                    <div class="block-header">
                        <h4 class="block-title">Последние списания / пополнения</h4>
                    </div>
                    <div class="block-body">
                        <ul class="events-list">
                            <li class="event">
                                <span data-icon="icon-outcoming-arrow"></span>
                                <span class="event-date">27 июля 2016</span>
                                <span class="event-title">Списание со счета</span>
                                <span class="event-cost">— 13 Р</span>
                            </li>
                            <li class="event">
                                <span data-icon="icon-outcoming-arrow"></span>
                                <span class="event-date">26 июля 2016</span>
                                <span class="event-title">Списание со счета</span>
                                <span class="event-cost">— 13 Р</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </section>
    </div>

    <section class="user-subscription-channels" data-module="user-subscription-channels">
        <script type="text/x-config">
            { "url": "/local/components/hawkart/subscription.channels/templates/.default/ajax.php" }
        </script>
        <div class="block-header">
            <h3 class="block-title">Подписки на каналы</h3>
        </div>
        <div class="block-body">
            <ul class="channels-list">
                <li class="item status-active" data-channel-id="16" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-russia-1-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">Россия 1</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="27" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-ren-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">РЕН ТВ</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="28" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-1st-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">Первый канал</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="29" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-ntv-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">НТВ</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="31" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-tvc-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">ТВ Центр</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="39" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-rtr-kultura-eng-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">Россия-К</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="40" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-matchtv-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">Матч</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="52" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-karusel-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">Карусель</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="61" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-zvezda-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">Звезда</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="62" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-tv3-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">ТВ-3</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="65" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-tnt-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">ТНТ</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="73" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-ctc-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">СТС</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="74" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-5th-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">5 канал</span>
                    </div>
                </li>
                <li class="item status-active" data-channel-id="75" data-type="channel-item">
                    <div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
                    <div class="subscription-logo">
                        <span data-icon="icon-mirtv-channel"></span>
                    </div>
                    <div class="item-header">
                        <span class="price">0 Р <small>сутки</small></span>
                        <span class="item-title">Мир</span>
                    </div>
                </li>
            </ul>
        </div>
    </section>
</main>

<?
	$js = [
		"js/user-services.js"
	];
	require("include/footer.php");
?>