<?
IncludeModuleLangFile(__FILE__);
class CProgTime
{
    public static $cacheDir = "prog_time";
    
    public static function generateUnique($arFields)
    {
        if(strlen($arFields["DATE_START"])==10)
            $arFields["DATE_START"].= " 00:00:00";
        
        $arFields["DATE_START"] = preg_replace("/[^0-9]/", '', $arFields["DATE_START"]);

        $str = $arFields["CHANNEL"].$arFields["DATE_START"];
        return $str;
    }
    
    public static function getList($arrFilter = false, $arSelect = array())
    {
        CModule::IncludeModule("iblock");
        $arProgTimes = array();
        
        if(empty($arSelect))
            $arSelect = Array("ID", "NAME", "PROPERTY_CHANNEL", "PROPERTY_DATE_START");
            
        $arFilter = array("IBLOCK_ID" => PROG_TIME_IB, "ACTIVE" => "Y");
        if($arrFilter)
            $arFilter = array_merge($arFilter, $arrFilter);

        
        $CacheEx = new CCacheEx(60*60*24*2, self::$cacheDir);
        $arTmpProgTimes = $CacheEx->cacheElement( array( "PROPERTY_DATE_START" => "ASC"), $arFilter, "getlist", false, $arSelect);
        foreach( $arTmpProgTimes as $arTmpProgTime )
        {
            $unique = self::generateUnique(array(
                "CHANNEL" => $arTmpProgTime["PROPERTY_CHANNEL_VALUE"],
                "DATE_START" => $arTmpProgTime["PROPERTY_DATE_START_VALUE"],
            ));
            
			$arProgTimes[$unique] = $arTmpProgTime;
		}
        
        return $arProgTimes;
    }
    
    public static function add($arFields)
    {
        CModule::IncludeModule("iblock");
        $el = new CIBlockElement;
        
        $PROP = array();
        $PROP = $arFields["PROPS"];
        $PROP["DATE_START"] = date("d.m.Y H:i:s", strtotime($PROP["DATE_START"]));
        $PROP["DATE_END"] = date("d.m.Y H:i:s", strtotime($PROP["DATE_END"]));
        
        //$datetime = date("dmY-Hi", strtotime($PROP["DATE_START"]));
        $datetime = preg_replace("/[^0-9]/", '', $PROP["DATE_START"]);
        $date = substr($datetime, 0, 2).substr($datetime, 2, 2);
        $time = substr($datetime, 8, 2).substr($datetime, 10, 2);
        
        if(strlen($time)==2) $time.="00";
        
        $arFields["FIELDS"]["NAME"] = trim($arFields["FIELDS"]["NAME"]);
        $arParams = array("replace_space"=>"-", "replace_other"=>"-");
        $translit = CDev::translit(trim($date."-".$time."-".$PROP["CHANNEL"]."-".$arFields["FIELDS"]["NAME"]), "ru", $arParams);
        
        $arLoadProductArray = Array(
            "IBLOCK_SECTION_ID" => false,
            "IBLOCK_ID"      => PROG_TIME_IB,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $arFields["FIELDS"]["NAME"],
            "CODE"           => $translit,   
            "ACTIVE"         => "Y",
        );
        
        $arLoadProductArray = array_merge($arLoadProductArray, $arFields["FIELDS"]);
        $progTimeID = $el->Add($arLoadProductArray);
        if($progTimeID)
        {
            return $progTimeID;
        }else{
            
            CDev::log(array(
                "ERROR" => $el->LAST_ERROR,
                "PROG" => $arLoadProductArray+array("DATE_TIME"=>$datetime),
            ));
            
            return $el->LAST_ERROR;
        }
    }
    
    public static function delete($arrFilter = false) 
    {
		CModule::IncludeModule("iblock");
        $arProgTimes = array();
        $arSelect = Array("ID");
        $arFilter = array("IBLOCK_ID" => PROG_TIME_IB, "ACTIVE" => "Y", "<PROPERTY_DATE"=>date('Y-m-d', strtotime('-1 day'))); 
        
        if($arrFilter)
            $arFilter = array_merge($arFilter, $arrFilter);
        
        $rsRes = CIBlockElement::GetList( array("SORT" => "ASC"), $arFilter, false, false, $arSelect);
		while( $arItem = $rsRes->GetNext() )
        {
            CIBlockElement::Delete($arItem["ID"]);
		}
        
        self::updateCache();
	}
    
    public static function getProgInfoIndex($arProg)
    {
        if($arProg["CLASS"]=="double")
        {
            $arProg["PICTURE"]["SRC"] = CFile::GetPath($arProg["PROPERTY_PICTURE_DOUBLE_VALUE"]);
        }
        
        if($arProg["CLASS"]=="one")
        {
            $arProg["PICTURE"]["SRC"] = CFile::GetPath($arProg["PREVIEW_PICTURE"]);
        }
        
        if($arProg["CLASS"]=="half")
        {
            $arProg["PICTURE"]["SRC"] = CFile::GetPath($arProg["PROPERTY_PICTURE_HALF_VALUE"]);
        }
        
        ob_start();
        ?>
        <div class="item status-recordable <?if(!empty($arProg["PICTURE"]["SRC"])):?> is-noimage<?endif;?><?if($arProg["CLASS"]=="double"):?> double-item<?endif;?>">
            <div class="item-image-holder" style="background-image: url(<?=$arProg["PICTURE"]["SRC"]?>"></div>
        	<span class="item-status-icon">
        		<span data-icon="icon-recordit"></span>
        	</span>
        	<div class="item-header">
        		<time><?=substr($arProg["DATE_START"], 11, 5)?></time>
        		<a href="<?=$arProg["DETAIL_PAGE_URL"]?>"><?=$arProg["NAME"]?></a>
        	</div>
        </div>
        <?
        $content = ob_get_contents();  
        ob_end_clean();
        
        return $content;
    }
    
    public static function getProgInfoChannel($arProg, $arParams)
    {        
        if($arProg["CLASS"]=="one")
        {
            $arProg["PICTURE"]["SRC"] = CFile::GetPath($arProg["PROPERTY_PICTURE_VERTICAL_DOUBLE_VALUE"]);
        }
        
        if($arProg["CLASS"]=="half")
        {
            $arProg["PICTURE"]["SRC"] = CFile::GetPath($arProg["PROPERTY_PICTURE_VERTICAL_VALUE"]);
        }
        
        ob_start();
        $start = $arProg["DATE_START"];
        $end = $arProg["DATE_END"];
        $datetime = CTimeEx::dateOffset($arParams["OFFSET"], $arParams["DATETIME_REAL"]);
        ?>
        <div class="item status-recordable <?if($arProg["CLASS"]=="half"):?> half-item<?endif;?>" data-type="draggable" data-target="drop-area">
			<div class="item-image-holder" style="background-image: url(<?=$arProg["PICTURE"]["SRC"]?>)"></div>
			
            <?if(CTimeEx::dateDiff($start, $datetime) && CTimeEx::dateDiff($datetime, $end)):?>
                <span class="badge">в эфире</span>
            <?endif;?>
            
            <?if($arProg["PROPERTY_HD_VALUE"]):?>
                <span class="badge">HD</span>
            <?endif;?>
            
			<span class="item-status-icon">
				<span data-icon="icon-recordit"></span>
				<span class="status-desc">Записать</span>
			</span>
			<div class="item-header">
                <?if(CTimeEx::dateDiff($start, $datetime) && CTimeEx::dateDiff($datetime, $end)):?>
                    <?
                    $allSecs = strtotime($end) - strtotime($start);
                    $secs = strtotime($datetime) - strtotime($start);
                    
                    $proc = ceil($secs/($allSecs/100));
                    $arTime = CTimeEx::secToTime($secs);
                    ?>
    				<div class="timeline" data-progress="<?=$proc?>">
    					<span class="progress-bg"></span>
    					<span>прошло <?/*if($arTime["h"]):?><?=$arTime["h"]?> ч. <?endif;*/?><?=$arTime["i"]?> мин.</span>
    				</div>
                <?endif;?>
				<span class="descr-trigger" data-type="descr-trigger"><span>&times;</span></span>
				<time><?=substr($arProg["DATE_START"], 11, 5)?></time>
				<a href="<?=$arProg["DETAIL_PAGE_URL"]?>">
                    <?=$arProg["NAME"]?>.<br>
                    <?if(!empty($arProg["PROPERTY_SUB_TITLE_VALUE"])):?><?=$arProg["PROPERTY_SUB_TITLE_VALUE"]?>.<?endif;?> 
                    <?if(!empty($arProg["PROPERTY_YEAR_VALUE"])):?>(<?=$arProg["PROPERTY_YEAR_VALUE"]?>)<?endif;?>
                </a>
				<div class="item-descr">
					<p><?=strip_tags($arProg["PREVIEW_TEXT"])?></p>
				</div>
			</div>
		</div>
        <?
        $content = ob_get_contents();  
        ob_end_clean();
        
        return $content;
    }
    
    public static function updateCache() 
    {
		CCacheEx::clean(self::$cacheDir);
	}
}