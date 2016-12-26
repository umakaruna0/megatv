<?
// подключим все необходимые файлы:
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php"); // первый общий пролог

IncludeModuleLangFile(__FILE__);

$sTableID = "tbl_serials_not_included"; // ID таблицы
$oSort = new CAdminSorting($sTableID, "ID", "ASC"); // объект сортировки
$lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка

$lAdmin->AddHeaders(array(
    array(  
        "id"    => "ID",
        "content"  => "#",
        //"sort"     => "id",
        "default"  => true,
    ),
    array(  
        "id"    => "ORIGINAL_TITLE",
        "content"  => "Оригинальное название передачи",
        //"sort"     => "ORIGINAL_TITLE",
        "default"  => true,
    ),
    array(  
        "id"    => "SOCIAL_TITLE",
        "content"  => "Название из ютуба",
        //"sort"     => "SOCIAL_TITLE",
        "default"  => true,
    ),
    array(  
        "id"    => "PERCENT",
        "content"  => "Процент совпадения, %",
        //"sort"     => "PERCENT",
        "align"    => "right",
        "default"  => true,
    ),
    array(  
        "id"    => "TIME",
        "content"  => "Время проигрывания в ютубе",
        //"sort"     => "TIME",
        "default"  => true,
    ),
    array(  
        "id"    => "ACTION",
        "content"  => "Действия",
        "default"  => true,
    )
));


$key = 1;
$file = $_SERVER['DOCUMENT_ROOT']."/upload/serials_50_60.txt";
$json = file_get_contents($file);
$arProgs = json_decode($json, true);
foreach($arProgs as $arProg)
{
    // создаем строку. результат - экземпляр класса CAdminListRow
    $row =& $lAdmin->AddRow($arProg["EXTERNAL_ID"], $arProg); 
    
    $row->AddViewField("ID", $key);
    $row->AddViewField("ORIGINAL_TITLE", $arProg["ORIGINAL_TITLE"]);
    $row->AddViewField("SOCIAL_TITLE", $arProg["SOCIAL_TITLE"]);
    $row->AddViewField("PERCENT", intval($arProg["PERCENT"]));
    $row->AddViewField("TIME", $arProg["TIME"]);
    $row->AddViewField("ACTION", '<a href="#" class="action-serial" data-serial="'.$arProg["UF_SERIAL_ID"].'" data-youtube-id="'.$arProg["EXTERNAL_ID"].'">Сохранить в БД</a>');
    $row->AddViewField("ORIGINAL_TITLE", $arProg["ORIGINAL_TITLE"]);
    
    $key++;
}

// альтернативный вывод
$lAdmin->CheckListMode();
$APPLICATION->SetTitle("Сериалы не загруженные, совпадение меньше 60%");
require_once ($DOCUMENT_ROOT.BX_ROOT."/modules/main/include/prolog_admin_after.php");

// выведем таблицу списка элементов
$lAdmin->DisplayList();
CJSCore::Init( 'jquery' ); 
?>
    <script type="text/javascript">
        $( function() {
            
            $(".action-serial").on("click", function(e){
                e.preventDefault();
                var _this = $(this);
                $.ajax({
                    type: "POST",
                    url: "/local/admin/ajax/serials_not_included.php",
                    data: {"serial_id" : $(this).data("serial"), "external_id" : $(this).data("youtube-id")},
                    success: function(data) 
                    {
                        _this.closest("tr").remove();
                    }
                });
            });
        } );
     
    </script>
<?
// завершение страницы
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>