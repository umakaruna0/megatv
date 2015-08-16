<?
IncludeModuleLangFile(__FILE__);
class CCacheEx
{
    public function __construct($cache_time, $cache_path)
    {
        $this->cache_path = $cache_path;
        $this->cache_time = $cache_time;
    }
    
    public function cacheElement( $arOrder, $arrFilter, $tag_cache = '', $limit, $arSelect )
    {
        global $CACHE_MANAGER;
        
        $obCache = new CPHPCache;
		$cache_time = $this->cache_time;
		$cache_path = $this->cache_path;
        
        $arRes = array();
        $cache_id = 'cache_'.serialize( $arOrder ).serialize( $arrFilter ).serialize( $limit ).serialize( $arSelect );
		if($cache_time > 0 && $obCache->InitCache($cache_time, $cache_id, $cache_path))
        {
			$arRes = $obCache->GetVars();
		}
        elseif($obCache->StartDataCache())
        {
            $arLimit = false;
			if( intval( $limit ) > 0 )
            {
				$arLimit = array( "nTopCount" => $limit );
			}
			$rsRes = CIBlockElement::GetList( $arOrder, $arrFilter, false, $arLimit, $arSelect );
			while( $arItem = $rsRes->GetNext() )
            {
                $arRes[] = $arItem;
			}
            
			$obCache->EndDataCache($arRes); 
		}
  
		return $arRes;
	}
    
    public static function clean($cache_path) 
    {
		global $CACHE_MANAGER;
		$obCache = new CPHPCache();
		$obCache->CleanDir($cache_path);
	}
}