<?
class CDev
{
    private static $firstRun = true;
    
	public static function console($data, $isAdmin = true)
    {
        global $USER;
        if(!is_object($USER)) 
            $USER = new CUser;
        
		if($isAdmin && !$USER->IsAdmin())
			return false;

		$backtrace = debug_backtrace();
		$callfrom = $backtrace[0]['file'].":".$backtrace[0]['line'];

		echo '<script>console.log("'.$callfrom.'", '.json_encode($data).');</script>';
		return true;
	}
	
	public static function pre($array, $echo = true, $isAdmin = true)
    {
        global $USER;
        if(!is_object($USER)) 
            $USER = new CUser;
        
		if($isAdmin && !$USER->IsAdmin())
			return false;
		
		$result = '<pre>'.print_r($array, true).'</pre>';
		
		if($echo)
        {
			echo $result;
			return true;
		}else{
			return $result;
		}
	}
	
	public static function var_dump($data, $echo = true, $isAdmin = true)
    {
        global $USER;
        if(!is_object($USER)) 
            $USER = new CUser;
        
		if($isAdmin && !$USER->IsAdmin())
			return false;
			
		ob_start();
		echo '<pre>'; var_dump($data); echo '</pre>';
		$result = ob_get_clean();
		
		if($echo)
			echo $result;
		else
			return $result;
	}
	
	public static function log($ar, $filename = LOG_FILENAME, $deleteOldData = true)
    {
		if(self::$firstRun && $deleteOldData)
			file_put_contents($_SERVER['DOCUMENT_ROOT'].$filename, print_r($ar, true)."\n\n");
		else
			file_put_contents($_SERVER['DOCUMENT_ROOT'].$filename, print_r($ar, true)."\n\n", FILE_APPEND);
            
		self::$firstRun = false;
	}
	
	public static function getElementById($id)
    {
        CModule::includeModule('iblock');
		if(!$obElement = CIBlockElement::getById($id)->getNextElement())
        {
			return false;
		}
		
		$element = $obElement->getFields();
		$element['PROPERTIES'] = $obElement->GetProperties();
		
		return $element;
	}
	
	public static function getElements($filter, $sort = array(), $groupBy = false, $needGetProperties = true)
    {
		CModule::includeModule('iblock');
	
		$elements = array();
		$rsElements = CIBlockElement::getList($sort, $filter, $groupBy);
		while($obElement = $rsElements->GetNextElement())
        {
			$element = $obElement->getFields();
			if(!empty($element['PREVIEW_PICTURE']))
				$element['PREVIEW_PICTURE'] = CFile::getFileArray($element['PREVIEW_PICTURE']);
				
			if(!empty($element['DETAIL_PICTURE']))
				$element['DETAIL_PICTURE'] = CFile::getFileArray($element['DETAIL_PICTURE']);
				
			if($needGetProperties)
				$element['PROPERTIES'] = $obElement->GetProperties();
				
			$elements[$element['ID']] = $element;
		}
		return $elements;
	}
	
	public static function getSections($filter, $sort = array(), $cnt = false, $select = array())
    {
		CModule::includeModule('iblock');
	
		$sections = array();
		$rsSections = CIBlockSection::getList($sort, $filter, $cnt, $select);
		while($section = $rsSections->GetNext())
        {
			if(!empty($section['PICTURE']))
				$section['PICTURE'] = CFile::getFileArray($section['PICTURE']);
				
			if(!empty($section['DETAIL_PICTURE']))
				$section['DETAIL_PICTURE'] = CFile::getFileArray($section['DETAIL_PICTURE']);
				
			$sections[$section['ID']] = $section;
		}
		return $sections;
	}
	
	public static function getNotEmptySections($filter = array('sections' => array(), 'elements' => array()), $sort = array())
    {
        CModule::includeModule('iblock');
		$sections = array();
		
		$rsSections = CIBlockSection::getList($sort, $filter['sections']);
		while($section = $rsSections->getNext())
        {
			$sections[$section['ID']] = $section;
		}
		
		$tmpSections = $sections;
		$sections = array();
		$rsElements = CIBlockElement::getList(array('SORT'), $filter['elements'], array('IBLOCK_SECTION_ID'));
		while($element = $rsElements->getNext())
        {
			if($element['CNT'] > 0)
				$sections[$element['IBLOCK_SECTION_ID']] = $tmpSections[$element['IBLOCK_SECTION_ID']];
		}
		
		return $sections;
	}
	
	public static function iconv($from, $to, $data)
    {
		if(is_array($data))
        {
			foreach($data as &$v)
            {
				$v = nm::iconv($from, $to, $v);
			}
		}else
			$data = iconv($from, $to, $data);
		
		return $data;
	}
    
    public static function cache($cacheTime = 3600, $cacheId, $cacheDir, $func, $arg=array())
    {
		if(!$func) return;
        
		$obCache = new CPHPCache;
		if($cacheTime > 0 && $obCache->InitCache($cacheTime, $cacheId, $cacheDir))
        {
			$res = $obCache->GetVars();
		}
        elseif($obCache->StartDataCache())
        {
			$res = call_user_func_array($func, $arg);
			$obCache->EndDataCache($res); 
		}
		return $res;		
	}
    
    // Пользовательские свойства
    public static function getUserField ($entity_id, $value_id, $property_id) 
    {
        CModule::IncludeModule("iblock");
        $arUF = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields($entity_id, $value_id); 
        return $arUF[$property_id]["VALUE"]; 
    } 

    public static function setUserField ($entity_id, $value_id, $uf_id, $uf_value) 
    {
        CModule::IncludeModule("iblock");
        return $GLOBALS["USER_FIELD_MANAGER"]->Update($entity_id, $value_id, Array ($uf_id => $uf_value)); 
    }
    
    public static function GUID()
    {
        if (function_exists('com_create_guid') === true)
        {
            return trim(com_create_guid(), '{}');
        }
        return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}