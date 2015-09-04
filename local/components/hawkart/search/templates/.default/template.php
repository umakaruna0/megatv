<form action="#" class="search-form<?if($APPLICATION->GetCurDir()=="/personal/records/"):?> is-cabinet-search-form<?endif;?>" data-module="search-form">
    <script type="text/x-config">
        {
         "url": "<?= $templateFolder ?>/ajax.php?query=%QUERY",
         "wildcard": "%QUERY"
        }
    </script>
	<div class="form-group has-feedback">
		<label for="" class="sr-only">Поиск</label>
		<input type="text" data-type="search-field" name="q" id="" class="form-control" placeholder="Поиск">
		<span class="form-control-feedback"><span data-icon="icon-search"></span></span>
	</div>
</form>