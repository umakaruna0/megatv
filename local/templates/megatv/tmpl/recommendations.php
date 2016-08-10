<?
	$items = true;
	require("include/header.php");
?>

<main class="site-content">
    <section class="section-h1 hidden">
        <h1></h1></section>
    <div class="broadcasts-categories" data-module="broadcasts-categories">
        <script type="text/x-config">
            { "url": "/recommendations/" }
        </script>
        <div class="items">
            <a href="#" class="item active" data-type="item" data-category="all">
			Все
		</a>
            <a href="#novosti" class="item" data-type="item" data-category="novosti">
    			Новости    		</a>
            <a href="#d-s" class="item" data-type="item" data-category="d-s">
    			Д/с    		</a>
            <a href="#sketch-shou" class="item" data-type="item" data-category="sketch-shou">
    			Скетч-шоу    		</a>
            <a href="#tok-shou" class="item" data-type="item" data-category="tok-shou">
    			Ток-шоу    		</a>
            <a href="#t-s" class="item" data-type="item" data-category="t-s">
    			Т/с    		</a>
        </div>
        <div class="more" data-type="more">
            <span data-icon="icon-close"></span>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
    </div>

    <section class="recommended-broadcasts" data-module="recomended-broadcasts"><!-- "viewMoreUrl" : "/recommendations/?AJAX=Y", -->
            <script type="text/x-config">
	        { 
	        	"viewMoreUrl" : "/local/templates/megatv/tmpl/ajax/load_records.php",
	        	"recordingURL": "/local/templates/megatv/ajax/to_record.php",
	        	"countMax" : 12,
	            "lang":{
	                "record_title": "Записать",
	                "msg_out_of_space": "У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК",
	                "btn_order_of_space": "Заказать дополнительную емкость"
	            }
	        }
	    </script>
        <div class="broadcasts-list">
	    	<? if(!$items){ ?>
	    	<!-- ========================= Если рекоммендаций нет  ========================= -->
	    	<div class="empty-content">
	    		<h1 class="empty-content__title">Список рекомендаций пуст...</h1>
	    	</div>
	    	<!-- ======================= ! Если рекоммендаций нет !  ======================= -->
	    	<? }else{ ?>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="70186" data-category="novosti">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/53804_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">14:00</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Новости</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/rossiya-1/vesti/?event=70186">
                            Вести                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="70491" data-category="novosti">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/186225_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">18:00</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Новости</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/vechernie-novosti/?event=70491">
                            Вечерние новости                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="72799" data-category="d-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/77826_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">16:00</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Д/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/tv3/gadalka/?event=72799">
                            Гадалка | Кукла на смерть                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="72903" data-category="sketch-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/196069_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">18:00</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Скетч-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/domashniy/6-kadrov/?event=72903">
                            6 кадров                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="70187" data-category="novosti">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/198215_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">14:30</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Новости</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/rossiya-1/mestnoe-vremya-vesti-moskva/?event=70187">
                            Местное время. Вести-Москва                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="70193" data-category="tok-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/198277_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">18:15</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Ток-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/rossiya-1/pryamoy-efir/?event=70193">
                            Прямой эфир                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="70427" data-category="d-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/195772_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">18:00</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Д/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/ren-tv/samye-shokiruyushchie-gipotezy/?event=70427">
                            Самые шокирующие гипотезы                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="70487" data-category="tok-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/172515_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">14:35</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Ток-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/muzhskoe-zhenskoe/?event=70487">
                            Мужское/Женское                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="70493" data-category="tok-shou">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/172550_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">19:50</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Ток-шоу</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/pervyy-kanal/pust-govoryat/?event=70493">
                            Пусть говорят                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="72800" data-category="d-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/77825_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">16:30</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Д/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/tv3/gadalka/?event=72800">
                            Гадалка | Замолчи                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="72801" data-category="d-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/77824_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">17:00</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Д/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/tv3/gadalka/?event=72801">
                            Гадалка | Повар для Веры                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item status-recordable" data-type="broadcast" data-broadcast-id="72937" data-category="t-s">
                <div class="inner">
                    <div class="item-image-holder" style="background-image: url(/upload/epg_cut/384304_288_288.jpg)"></div>

                    <span class="item-status-icon js-btnModalInit" data-module="modal" data-modal="authURL" data-type="openModal">        
                            <span data-icon="icon-recordit"></span>
                    <span class="status-desc">Записать</span>
                    </span>
                    <div class="extend-drive-notify">
                        <div class="extend-drive-notify-text-wrap">
                            <span data-icon="icon-storage"></span>
                            <p>У Вас закончилось свободное место на облачном хранилище МЕГА.ДИСК</p>
                            <p><a href="/personal/services/">Заказать дополнительную емкость</a></p>
                        </div>
                    </div>

                    <div class="item-header">
                        <div class="meta">
                            <div class="time">18:00</div>
                            <div class="date">10.08.2016</div>
                            <div class="category"><a href="#" data-type="category">Т/с</a></div>
                        </div>
                        <div class="title">
                            <a href="/channels/sts/kukhnya/?event=72937">
                            Кухня                        </a>
                        </div>
                    </div>
                </div>
            </div>
            <? } ?>
        </div>
    </section>
</main>
<?
    $js = [
        "js/main.js"
    ];
    require("include/footer.php");
?>