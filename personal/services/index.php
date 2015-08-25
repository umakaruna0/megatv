<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("MegaTV");
global $USER;
?>

<div class="flex-row user-services-top-row">
					<section class="subscribtion-services" data-module="subscribtion-services">
						<div class="block-header">
							<h3 class="block-title">Ваши услуги</h3>
						</div>
						<div class="block-body">
							<div class="storage-statistic">
								<span class="total-space">Всего <strong>24 ГБ</strong></span>
								<span class="used-space">Занято <strong>19 ГБ</strong></span>
								<div class="progressbar-holder" data-progress="0.7916"></div><!-- 79.16%-->
							</div>
							<ul class="available-subscribtions">
								<li class="item">
									<a href="#" class="handler-link"></a>
									<div class="subscribtion-text-logo">+5 ГБ</div>
									<div class="item-header">
										<span class="price">1 Р <small>сутки</small></span>
										<span class="item-title">Добавить<br> + 5 ГБ памяти</span>
									</div>
								</li>
								<li class="item">
									<a href="#" class="handler-link"></a>
									<div class="subscribtion-text-logo">+10 ГБ</div>
									<div class="item-header">
										<span class="price">2 Р <small>сутки</small></span>
										<span class="item-title">Добавить<br> + 10 ГБ памяти</span>
									</div>
								</li>
								<li class="item">
									<a href="#" class="handler-link"></a>
									<div class="subscribtion-logo">
										<span data-icon="icon-gdisk-service"></span>
									</div>
									<div class="item-header">
										<span class="price">1 Р <small>сутки</small></span>
										<span class="item-title">Сохранение <br>на Гугл.Драйв</span>
									</div>
								</li>
								<li class="item status-active">
									<a href="#" class="handler-link"></a>
									<div class="subscribtion-logo">
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
					<section class="user-balance">
						<div class="block-header">
							<h3 class="block-title">Баланс</h3>
						</div>
						<div class="block-body">
							<div class="account-balance">
								<span data-icon="icon-balance"></span><small>На счету:</small> 2 312 Р
								<a href="#" class="btn btn-primary btn-block">Пополнить счет</a>
							</div>
							<div class="balance-history">
								<div class="block-header">
									<h4 class="block-title">Последние списания / пополнения</h4>
								</div>
								<div class="block-body">
									<ul class="events-list">
										<li class="event">
											<span data-icon="icon-incoming-arrow"></span>
											<span class="event-date">12 сентября 2015</span>
											<span class="event-title">Пополнение счета</span>
											<span class="event-cost">+ 1 500 Р</span>
										</li>
										<li class="event">
											<span data-icon="icon-outcoming-arrow"></span>
											<span class="event-date">12 сентября 2015</span>
											<span class="event-title">Пополнение счета</span>
											<span class="event-cost">— 2 250 Р</span>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</section>
				</div>
				<section class="subscribtion-channels">
					<div class="block-header">
						<h3 class="block-title">Подписки на каналы</h3>
					</div>
					<div class="block-body">
						<ul class="channels-list">
							<li class="item status-active">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-1st-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">0 Р <small>сутки</small></span>
									<span class="item-title">Первый канал</span>
								</div>
							</li>
							<li class="item status-active">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-russia-1-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">0 Р <small>сутки</small></span>
									<span class="item-title">Россия 1</span>
								</div>
							</li>
							<li class="item status-active">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-russia-2-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">0 Р <small>сутки</small></span>
									<span class="item-title">Россия 2</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-ntv-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">0 Р <small>сутки</small></span>
									<span class="item-title">НТВ</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-5th-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">0 Р <small>сутки</small></span>
									<span class="item-title">Пятый канал</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-domashnii-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">0 Р <small>сутки</small></span>
									<span class="item-title">Домашний</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-zvezda-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">0 Р <small>сутки</small></span>
									<span class="item-title">Звезда</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-karusel-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">0 Р <small>сутки</small></span>
									<span class="item-title">Карусель</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-mirtv-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">МИР</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-muz-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">МУЗ ТВ</span>
								</div>
							</li>
							<li class="item status-active">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-otr-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">Общественное телевидение России</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-ren-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">РЕН ТВ</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-rtr-kultura-eng-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">Культура</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-spas-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">СПАС</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-ntv-plus-sportplus-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">Спорт +</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-ctc-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">СТС</span>
								</div>
							</li>
							<li class="item status-active">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-tvc-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">ТВ ЦЕНТР</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-discovery-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">Discovery</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-discovery-animalplanet-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">Animal Planet</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-mtv-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">MTV</span>
								</div>
							</li>
							<li class="item">
								<a href="#" class="handler-link"></a>
								<div class="subscribtion-logo">
									<span data-icon="icon-peretz-channel"></span>
								</div>
								<div class="item-header">
									<span class="price">1 Р <small>сутки</small></span>
									<span class="item-title">Перец ТВ</span>
								</div>
							</li>
						</ul>
					</div>
				</section>
                <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>