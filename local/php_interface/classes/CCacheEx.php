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
		//$cache = new CPHPCache();
        $obCache = new CPHPCache;
		$cache_time = $this->cache_time;
		$cache_path = $this->cache_path;
        
        $arRes = array();
        $cache_id = 'cache_'.serialize( $arOrder ).serialize( $arrFilter ).serialize( $limit ).serialize( $arSelect );
		if($cache_time > 0 && $obCache->InitCache($cache_time, $cache_id, $cache_path))
        {
			$arRes = $obCache->GetVars();
            
            //print_r($arRes);
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
            
            if(count($arRes)==1)
            {
                $arRes = $arRes[0];            
            } 
            
			$obCache->EndDataCache($arRes); 
		}
        
        /*
        $arRes = array();
		if($cache->InitCache($cache_time, $cache_id, $cache_path) )
        {
			$res = $cache->GetVars();
			$arRes = $res["arRes"];
		}else{
			                                   
            
			if( COption::GetOptionString("main", "component_cache_on", "Y") == "Y" && $cache_time > 0 )
            {
				$cache->StartDataCache( $cache_time, $cache_id, $cache_path );
				
				if( !empty( $tag_cache ) )
                {
					global $CACHE_MANAGER;
					$CACHE_MANAGER->StartTagCache( $cache_path );
					$CACHE_MANAGER->RegisterTag( $tag_cache );
					$CACHE_MANAGER->EndTagCache();
				}
				
				$cache->EndDataCache( 
					array(
						"arRes" => $arRes
					)
				);
			}
		}*/
		return $arRes;
	}
    
    public static function clean($cache_path) 
    {
		global $CACHE_MANAGER;
		$obCache = new CPHPCache();
		$obCache->CleanDir($cache_path);
	}
}