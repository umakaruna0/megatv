<div class="box-search box-right__box-search" data-module="search">
    <script type="text/x-config">
        {
         "url": "<?= $templateFolder ?>/ajax.php?query=%QUERY",
         "wildcard": "%QUERY"
        }
    </script>
    <form action="#" class="box-search__form_search form form-search form-search--hide">
        <input type="search" required="" data-type="search-field" name="q" class="form-search__input-search form__form-control input-search" value="" placeholder="Название программы или сериала...">
        <div class="form-search__group-btn">
            <button type="submit" class="form-search__btn-search btn-open-form">
                <span data-icon="icon-search" class="g-icon g-icon--20px icon-search form-search__icon"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-search"></use></svg></span>
            </button>
            <button data-type="open" type="button" class="form-search__btn-search btn-close-form js-open-search-form">
                <span data-icon="icon-search" class="g-icon g-icon--20px icon-search form-search__icon"><svg class="g-icon__icon-cnt"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-search"></use></svg></span>
            </button>
        </div>
    </form>
</div>

<!-- <div class="search" data-module="search">
    <script type="text/x-config">
        {
         "url": "<?= $templateFolder ?>/ajax.php?query=%QUERY",
         "wildcard": "%QUERY"
        }
    </script>
    <form action="#" class="search-form<?/*if($APPLICATION->GetCurDir()=="/personal/records/"):?> is-cabinet-search-form<?endif;*/?>">
    	<div class="form-group has-feedback" data-type="search-group">
    		<label for="" class="sr-only">Название программы или сериала</label>
    		<input type="text" data-type="search-field" name="q" id="" class="form-control" placeholder="Название программы или сериала">
    		<span data-icon="icon-search"></span>
    	</div>
        <div class="search-close" data-type="close">
		</div>
    </form>
    <div class="search-trigger" data-type="open">
		<span data-icon="icon-search"></span>
	</div>
</div>			 -->