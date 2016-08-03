<?require("include/header.php");?>

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
	    </div>
	    <div class="more" data-type="more">
	        <span data-icon="icon-close"></span>
	        <div class="circle"></div>
	        <div class="circle"></div>
	        <div class="circle"></div>
	    </div>
	</div>

	<section class="recommended-broadcasts" data-module="broadcast-results">
	    <script type="text/x-config">
	        { "recordingURL": "/local/templates/megatv/ajax/to_record.php" }
	    </script>
	    <div class="broadcasts-list">
	    	<!-- ========================= Если рекоммендаций нет  ========================= -->
	    	<div class="empty-content">
	    		<h1 class="empty-content__title">Список рекомендаций пуст...</h1>
	    	</div>
	    	<!-- ======================= ! Если рекоммендаций нет !  ======================= -->
	    </div>
	</section>

</main>

<?
    $js = [
        "js/main.js"
    ];
    require("include/footer.php");
?>