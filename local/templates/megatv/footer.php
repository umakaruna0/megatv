<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

            </main>
			<footer class="site-footer">
				<div class="fullsize-banner adv-styling-01" data-type="advertizing" id="footer-adv">
					<div class="banner-content">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer-banner.php"), false);?>
					</div>
					<a href="#" class="close-link" data-type="hide-banner-link">Скрыть</a>
				</div>
				<div class="footer-content">
					<span class="footer-logo"></span>
					<p class="copyrights">
                        <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer-copyright.php"), false);?>
                    </p>
				</div>
			</footer>
			<div class="drop-overlay"></div>
		</div><!-- /.site-wrapper -->
        
        <?
        //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/vendor.js');
        //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/plugins.js');
        //$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/main.js');
        ?>
        <script src="<?=SITE_TEMPLATE_PATH?>/js/vendor.js"></script>
		<script src="<?=SITE_TEMPLATE_PATH?>/js/plugins.js"></script>
		<script src="<?=SITE_TEMPLATE_PATH?>/js/main.js"></script>
        <script src="<?=SITE_TEMPLATE_PATH?>/project.js"></script>
	</body>
</html>