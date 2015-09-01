<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("MegaTV");
global $USER;
?>
<div class="flex-row user-services-top-row">
					<section class="subscription-services" data-module="subscription-services">
						<script type="text/x-config">
							{
								"filledDiskSpace" : "0.7916",
								"url": "/server/"
							}
						</script>
						<div class="block-header">
							<h3 class="block-title">Ваши услуги</h3>
						</div>
						<div class="block-body">
							<div class="storage-statistic">
								<span class="total-space">Всего <strong>24 ГБ</strong></span>
								<span class="used-space">Занято <strong>19 ГБ</strong></span>
								<div class="progressbar-holder"></div>
							</div>
							<ul class="available-subscriptions">
								<li class="item" data-service-id="01" data-type="service-item">
									<div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
									<div class="subscription-text-logo">+5 ГБ</div>
									<div class="item-header">
										<span class="price">1 Р <small>сутки</small></span>
										<span class="item-title">Добавить<br> + 5 ГБ памяти</span>
									</div>
								</li>
								<li class="item" data-service-id="02" data-type="service-item">
									<div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
									<div class="subscription-text-logo">+10 ГБ</div>
									<div class="item-header">
										<span class="price">2 Р <small>сутки</small></span>
										<span class="item-title">Добавить<br> + 10 ГБ памяти</span>
									</div>
								</li>
								<li class="item" data-service-id="03" data-type="service-item">
									<div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
									<div class="subscription-logo">
										<span data-icon="icon-gdisk-service"></span>
									</div>
									<div class="item-header">
										<span class="price">1 Р <small>сутки</small></span>
										<span class="item-title">Сохранение <br>на Гугл.Драйв</span>
									</div>
								</li>
								<li class="item status-active" data-service-id="04" data-type="service-item">
									<div class="decor"><span class="decor-title">Добавить</span><span data-icon="icon-round-checkbox-mark"></span></div>
									<a href="#" class="handler-link"></a>
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
                
                <?$APPLICATION->IncludeComponent("hawkart:subscription.channels", "", Array(), false);?>
                
                <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>