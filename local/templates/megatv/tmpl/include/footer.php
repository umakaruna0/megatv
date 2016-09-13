        <footer class="site-footer">
            <div class="footer-content">
                <div class="footer-col">
                </div>
                <div class="footer-col footer-col--large">
                </div>
                <div class="copyrights">
                    <a href="#">Условия использования сервиса</a> © 2014—2016
                </div>
            </div>
        </footer>
        <div class="drop-overlay"></div>
    </div>
    <!-- END SITE-WRAPPER -->

    <!-- FIXED - HEADER -->
    <div class="fixed-header" hidden>
        <div class="fixed-header__f-menu f-menu">
            <div class="box-right__box-menu">
                <a class="box-menu__link menu-link" href="channels.php">
                    <span data-icon="icon-channels" class="menu-link__icon g-icon"></span>
                    <span class="box-menu__title">Каналы</span>
                </a>
                <a class="box-menu__link menu-link" href="recommendations.php">
                    <span data-icon="icon-recommendations" class="menu-link__icon g-icon"></span>
                    <span class="box-menu__title">Рекомендации</span>
                </a>
                <a class="box-menu__link menu-link item-recording" href="personal-records.php">
                    <span data-icon="icon-film-collection" class="menu-link__icon g-icon"></span>
                    <div class="box-menu__title"><div class="item-recording__count">0 из 0</div> Мои записи</div>
                </a> 
            </div>
        </div>
    </div>

    <? foreach( $js as $val ){ ?>
        <script src="<?=$val;?>"></script>
    <? } ?>
    <script>
    $(document).ready(function(){
        // if($("*").hasClass("main-broadcasts--slider")) 
            // var swiper = new Swiper('.swiper-container', {
            //     scrollbar: '.swiper-scrollbar',
            //     slidesPerView: "auto",
            //     scrollbarHide: true,
            //     keyboardControl: true,
            //     nextButton: '.swiper-button-next',
            //     prevButton: '.swiper-button-prev',
            //     spaceBetween: 0,
            //     hashnav: true,
            //     grabCursor: false,
            //     freeMode: true
            // });   
    });
    </script>

</body>
</html>