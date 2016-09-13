<?
    $auth = true;
	require("include/header.php");
?>

<main class="site-content">
    <section class="section-h1 hidden">
        <h1></h1></section>
    <div class="broadcasts-categories" data-module="broadcasts-categories">

        <script type="text/x-config">
            { "url": "personal-records.php" }
        </script>

        <div class="items">
            <a href="#" class="item active" data-type="item" data-category="all">Все</a>
            <a href="#d-s" class="item" data-type="item" data-category="d-s">Д/с</a>
            <a href="#novosti" class="item" data-type="item" data-category="novosti">Новости</a>
            <a href="#tok-shou" class="item" data-type="item" data-category="tok-shou">Ток-шоу</a>
            <a href="#t-s" class="item" data-type="item" data-category="t-s">Т/с</a>
            <a href="#poznavatelnoe" class="item" data-type="item" data-category="poznavatelnoe">Познавательное</a>
            <a href="#tv-shou" class="item" data-type="item" data-category="tv-shou">ТВ-шоу</a>
            <a href="#yumor" class="item" data-type="item" data-category="yumor">Юмор</a>
            <a href="#sport" class="item" data-type="item" data-category="sport">Спорт</a>
        </div>
        <div class="more" data-type="more">
            <span data-icon="icon-close"></span>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>

    <section class="broadcasts" data-module="user-recorded-broadcasts">
        <!-- ============== Заменено ============== -->
        <script type="text/x-config">
            { 
            	"remoteUrl" : "<?=SITE_TEMPLATE_PATH?>/tmpl/ajax/modals/delete_record.php",
            	"viewMoreUrl" : "<?=SITE_TEMPLATE_PATH?>/tmpl/ajax/load_records.php",
            	"countMax" : 15,
                "lang":{
                    "delete_title": "Удалить",
                    "view_title": "Посмотреть",
                    "warn_msg_delete": "Вы уверены, что хотите удалить данную передачу навсегда?",
                    "confirm_delete_btn": "Да, хочу",
                    "cancel_btn": "Отменить"
                }
            }
        </script>
        <!-- ! ============== Заменено ============== ! -->

        <div class="broadcasts-list">
            <div class="item" data-broadcast-id="508" data-category="d-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">17:10</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Д/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/rossiya-k/ispanskiy-sled/">Испанский след | Эрнест Х...</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="509" data-category="novosti">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">17:00</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Новости</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/rossiya-1/vesti/">Вести</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="506" data-category="tok-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">15:15</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Ток-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/muzhskoe-zhenskoe/">Мужское/Женское</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="505" data-category="t-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">14:50</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Т/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/ntv/mentovskie-voyny-5/">Ментовские войны-5</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="502" data-category="poznavatelnoe">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">14:30</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Познавательное</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/tabletka/">Таблетка | Инсульт</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="503" data-category="t-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">13:50</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Т/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/ntv/kodeks-chesti-7/">Кодекс чести-7 | Книга</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="501" data-category="tv-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">13:25</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">ТВ-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/davay-pozhenimsya/">Давай поженимся!</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="507" data-category="yumor">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">14:00</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Юмор</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/tnt/comedy-woman/">Comedy Woman | Дайджест</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="504" data-category="novosti">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">13:20</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Новости</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/ntv/chp/">ЧП</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="500" data-category="tok-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">12:15</div>
                            <div class="date">19.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Ток-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/pust-govoryat/">Пусть говорят</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-viewed" data-broadcast-id="452" data-category="t-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <span class="item-status-icon">
							<span data-icon="icon-viewed"></span>
                    <span class="status-desc">Просмотрено</span>
                    </span>

                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">19:40</div>
                            <div class="date">11.07.2016</div>
                            <div class="category"><a href="#" data-type="category">Т/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/ntv/dikiy/">Дикий | Месть Дикого</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item" data-broadcast-id="404" data-category="sport">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url('http://tvguru.com.images.1c-bitrix-cdn.ru/upload/epg_cut/387512_288_288.jpg');"></div>
                    <div class="actions-panel">
                        <ul class="actions-list">
                            <li><a href="#" data-type="delete-trigger" title="Удалить"><span data-icon="icon-trash-action"></span></a></li>
                            <li><a href="#" data-type="player-trigger" title="Просмотреть"><span data-icon="icon-play-action"></span></a></li>
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
                        <div class="view-progress" data-progress="0"></div>

                        <div class="meta">
                            <div class="time">18:45</div>
                            <div class="date">22.06.2016</div>
                            <div class="category"><a href="#" data-type="category">Спорт</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/match/futbol-chempionat-evropy/">Футбол. Чемпионат Европы</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.broadcasts-list -->
    </section>
	<? require("include/content-bottom.php"); ?>
</main>

<?
	$js = [
		"js/user-records.js",
		"js/player.js"
	];
	require("include/footer.php");
?>