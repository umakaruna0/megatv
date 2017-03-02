<?
define('STOP_STATISTICS', true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$GLOBALS['APPLICATION']->RestartBuffer();

global $USER;
if(!is_object($USER))
    $USER = new \CUser;

if(!$USER->IsAuthorized() && empty($_REQUEST["url"]))
    return false;

/**
 * Показ прямого эфира канала
 */ 
if(isset($_REQUEST["channel_id"]))
{
    $result = \Hawkart\Megatv\ChannelTable::getList(array(
        'filter' => array("=ID" => intval($_REQUEST["channel_id"])),
        'select' => array(
            'ID', 'UF_TITLE' => 'UF_BASE.UF_TITLE', 
            'UF_STREAM_URL' => 'UF_BASE.UF_STREAM_URL'
        ),
        'limit' => 1
    ));
    $arChannel = $result->fetch();
    
    if(empty($arChannel["UF_STREAM_URL"]))
    {
        $arChannel["UF_STREAM_URL"] = $_REQUEST["url"];
        $arChannel["UF_TITLE"] = "Test";
    }
    ?>
    <div class="broadcast-player" data-module="broadcast-player">
    	<script type="text/x-config">
    		{
    			"seekTime": "0",
    			"broadcastID": "0",
    			"streamURL": "<?=$arChannel["UF_STREAM_URL"]?>",
    			"posterURL": "<?=$arChannel["UF_IMG_PATH"]?>",
    			"videoTitle": "<?=$arChannel["UF_TITLE"]?>",
                "playerFlashURL": "<?=SITE_TEMPLATE_PATH?>/tmpl/js/jwplayer/jwplayer.flash.swf"
    		}
    	</script>
        <a class="back-link" href="#" data-dismiss="modal">
    		<span data-icon="icon-backlink-arrow"></span>
    		<span>Вернуться назад</span>
    	</a>
    	<div class="block-header">
    		<h3 class="block-title"><?=$arChannel["UF_TITLE"]?></h3>
    	</div>
    	<div class="block-body">
    		<a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
    		<div class="player-holder">
    			<div id="player"></div>
    		</div>
    	</div>
    </div>
    <?
}else{
    
    if(!preg_match("/^[\d\+]+$/", $_GET["broadcastID"]))
    {
        $result = \Hawkart\Megatv\ProgExternalTable::getList(array(
            'filter' => array("=UF_EXTERNAL_ID" => $_GET["broadcastID"]),
            'select' => array("ID", "UF_TITLE", "UF_EXTERNAL_ID", "UF_THUMBNAIL_URL", "UF_VIDEO_URL", "UF_JSON")
        ));
        if ($row = $result->fetch())
        {
            $arVideo["VIDEO_URL"] = $row["UF_VIDEO_URL"];
            
            if(strpos($arVideo["VIDEO_URL"], "rutube")!==false && !empty($arVideo["VIDEO_URL"]))
            {
                $arVideo["VIDEO_URL"] = str_replace("play", "video", $arVideo["VIDEO_URL"])."?sTitle=false&sAuthor=false";
            }
            else if(strpos($arVideo["VIDEO_URL"], "youtube")!==false)
            {
                $arVideo["VIDEO_URL"] = str_replace("watch?v=", "v/", $arVideo["VIDEO_URL"]);
            }else{
                $doc = new DOMDocument();
                $doc->loadHTML($row["UF_JSON"]["html"]);
                $arVideo["VIDEO_URL"] = $doc->getElementsByTagName('iframe')->item(0)->getAttribute('src');
            }
            $arVideo["NAME"] = $row["UF_TITLE"];
        }

        ?>
        <div class="broadcast-player" data-module="broadcast-player">
        	<script type="text/x-config">
        		{
        			"seekTime": "0",
        			"broadcastID": "0",
        			"streamURL": "<?=$arVideo["VIDEO_URL"]?>",
        			"posterURL": "<?=$arVideo["PLAYER_BG"]?>",
        			"videoTitle": "<?=htmlspecialchars($arVideo["NAME"])?>",
                    "playerFlashURL": "<?=SITE_TEMPLATE_PATH?>/tmpl/js/jwplayer/jwplayer.flash.swf"
        		}
        	</script>
            <a class="back-link" href="#" data-dismiss="modal">
        		<span data-icon="icon-backlink-arrow"></span>
        		<span>Вернуться назад</span>
        	</a>
        	<div class="block-header">
        		<h3 class="block-title"><?=$arVideo["NAME"]?></h3>
        	</div>
        	<div class="block-body">
        		<a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
        		<div class="player-holder">
                    <?if(strpos($_GET["broadcastID"], "youtube")!==false || strpos($arVideo["VIDEO_URL"], "youtube")!==false):?>
                        <div id="player"></div>
                    <?else:?>
                        <iframe src="<?=$arVideo["VIDEO_URL"]?>" width="896" height="504" frameborder="0" id="vk-player" class="flash"></iframe>
                        <div style="display: none !important"><div id="player"></div></div>
                        <script>
                            $(function(){
                                $(".close-link").click(function(){
                                    $("#vk-player").remove();
                                });
                            });
                        </script> 
                    <?endif;?>
        		</div>
                <div>
                    <p style="color: #fff;">
                        <?=$row["UF_JSON"]["description"]?>
                        <?if(strpos($arVideo["VIDEO_URL"], "youtube")!==false):?>
                            <br />
                            Ссылка на источник:: <a href="<?=$arVideo["VIDEO_URL"]?>" target="_blank" style="color: #fff;"><?=$arVideo["VIDEO_URL"]?></a>
                        <?endif;?>
                    </p>
                </div>
        	</div>
        </div>
        <?
    }else{
        
        /**
         * Показ передачи
         */
        $broadcastID = intval($_GET["broadcastID"]);
        
        if($_GET["record"]!="false")
        {
            $arFilter = array("=ID" => $broadcastID);
        }else{
            $arFilter = array("=UF_USER_ID" => $USER->GetID(), "=UF_SCHEDULE_ID" => $broadcastID);
        }
        
        $result = \Hawkart\Megatv\RecordTable::getList(array(
            'filter' => $arFilter,
            'select' => array(
                "ID", "UF_PROG_ID", "UF_URL", "UF_PROGRESS_SECS",
                "UF_TITLE" => "UF_PROG.UF_TITLE", "UF_SUB_TITLE" => "UF_PROG.UF_SUB_TITLE",
                "UF_IMG_PATH" => "UF_PROG.UF_IMG.UF_PATH",
            ),
            'limit' => 1
        ));
        $arRecord = $result->fetch();
        $arRecord["UF_NAME"] = \Hawkart\Megatv\ProgTable::getName($arRecord);
        $arRecord["PICTURE"]["SRC"] = \Hawkart\Megatv\CFile::getCropedPath($arRecord["UF_IMG_PATH"], array(300, 300), true);
        
        //get count watched
        $countWatched = 0;
        $result = \Hawkart\Megatv\RecordTable::getList(array(
            'filter' => array("UF_WATCHED"=> 1, "=UF_PROG_ID"=>$arRecord["UF_PROG_ID"]),
            'select' => array(
                new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)', array('ID'))
            )
        ));
        $arWatched = $result->fetch();
        ?>
        <div class="broadcast-player" data-module="broadcast-player">
        	<script type="text/x-config">
        		{
        			"seekTime": "<?=intval($arRecord["UF_PROGRESS_SECS"])?>",
        			"broadcastID": "<?=$broadcastID?>",
        			"streamURL": "<?=$arRecord["UF_URL"]?>",
        			"posterURL": "<?=$arRecord["PICTURE"]["SRC"]?>",
        			"videoTitle": "<?=$arRecord["UF_NAME"]?>",
                    "playerFlashURL": "<?=SITE_TEMPLATE_PATH?>/tmpl/js/jwplayer/jwplayer.flash.swf"
        		}
        	</script>
            <a class="back-link" href="#" data-dismiss="modal">
        		<span data-icon="icon-backlink-arrow"></span>
        		<span>Вернуться назад</span>
        	</a>
        	<div class="block-header">
        		<h3 class="block-title"><?=$arRecord["UF_TITLE"]?><?= $arRecord["UF_SUB_TITLE"] ? " <small>|".$arRecord["UF_SUB_TITLE"]."</small>" : "" ?></h3>
                <?//=$arRecord["UF_URL"]?>
            </div>
        	<div class="block-body">
        		<a href="#" class="close-link" data-dismiss="modal"><span data-icon="icon-times"></span></a>
        		<div class="player-holder">
        			<div id="player"></div>
        			<div class="player-panel">
        				<dl class="view-count">
        					<dt>Просмотров:</dt>
        					<dd><?=intval($arWatched["CNT"])?></dd>
        				</dl>
        				<?/*<dl class="download-count">
        					<dt>Скачиваний:</dt>
        					<dd>9 157 000</dd>
        				</dl>
        				<div class="social-share">
        					<span>Рассказать друзьям:</span>
        					<ul class="socuals-list">
        						<li><a href="#"><span data-icon="icon-vk-social"></span></a></li>
        						<li><a href="#"><span data-icon="icon-fb-social"></span></a></li>
        						<li><a href="#"><span data-icon="icon-tw-social"></span></a></li>
        						<li><a href="#"><span data-icon="icon-gp-social"></span></a></li>
        					</ul>
        				</div>
        				<a href="broadcast-card-recommendate.html" class="btn btn-default"><span data-icon="icon-network-social"></span>Рекомендовать друзьям</a>*/?>
        			</div>
        		</div>
                
  
                <?/*<div id="hypercomments_widget"></div>
                <script type="text/javascript">
                _hcwp = window._hcwp || [];
                _hcwp.push({widget:"Stream", widget_id: 87694});
                (function() {
                if("HC_LOAD_INIT" in window)return;
                HC_LOAD_INIT = true;
                var lang = (navigator.language || navigator.systemLanguage || navigator.userLanguage || "en").substr(0, 2).toLowerCase();
                var hcc = document.createElement("script"); hcc.type = "text/javascript"; hcc.async = true;
                hcc.src = ("https:" == document.location.protocol ? "https" : "http")+"://w.hypercomments.com/widget/hc/87694/"+lang+"/widget.js";
                var s = document.getElementsByTagName("script")[0];
                s.parentNode.insertBefore(hcc, s.nextSibling);
                })();
                </script>*/?>
                
                <div id="disqus_thread"></div>
                <script>
                
                /**
                *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
                *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
                
                var disqus_config = function () {
                //this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
                this.page.identifier = <?=$arRecord["UF_PROG_ID"]?>; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                };
                
                (function() { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script');
                s.src = '//tvguru-com.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
                })();
                </script>
                <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
                
                <?//$APPLICATION->IncludeComponent("hawkart:prog.comments", "", Array("PROG_ID"=>$arRecord["UF_PROG_ID"]), false);?>
        	</div>
        </div><!-- .broadcast-player -->
        <?
    }
}
?>
<?die();?>