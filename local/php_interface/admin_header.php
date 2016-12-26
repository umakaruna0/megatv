<? if( ( $_SERVER['REQUEST_URI'] == '/bitrix/admin/' ) || strstr( $_SERVER['REQUEST_URI'], 'dt_page' ) ): ?>
    <? CJSCore::Init( 'jquery' ); ?>
    <script type="text/javascript">
        $( function() {
            /**
             * Добавляем кнопку
             */
            $( '.adm-btn.adm-btn-desktop-gadgets.adm-btn-menu' ).before( '<a title="" class="adm-btn adm-btn-desktop-gadgets adm-btn-test-btn" hidefocus="true" href="/bitrix/admin/serials_not_included.php">Кнопка</a>' );
        } );
     
    </script>
    <style>
        .adm-btn-test-btn{
            margin-right: 15px;
        }
    </style>
<? endif; ?>