<?
$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . '/../../../');
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
set_time_limit(0);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $USER, $APPLICATION;
if (!is_object($USER))
    $USER=new CUser;

header("Pragma-directive: no-cache");
header("Cache-directive: no-cache");
header("Cache-control: no-cache");
header("Pragma: no-cache");
header("Expires: 0");


/*\Hawkart\Megatv\ProgTable::update(1103, array(
    "UF_IMG_LIST" => array("one"=> array(1,2,3), "double" => array() )
));*/

?>

<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Cropper</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/css/tether.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="cropper/dist/cropper.css">
  <style>
/* Basic */

body {
  margin: 0;
  overflow-x: hidden;
}

.browserupgrade {
  margin: 0;
  padding: .5rem 1rem;
  background-color: #fcfcfc;
  text-align: center;
}

.btn {
  padding-left: .75rem;
  padding-right: .75rem;
}

label.btn {
  margin-bottom: 0;
}

.d-flex > .btn {
  flex-grow: 1;
}


/* Jumbotron */

.docs-jumbotron {
  background-color: #0074d9;
  border-radius: 0;
  color: #fff;
}

.docs-jumbotron .version {
  font-size: .875rem;
  color: #fff;
  filter: alpha(opacity=50);
  opacity: 0.5;
}

@media (min-width: 992px) {
  .docs-jumbotron h1,
  .docs-jumbotron p {
    margin-right: 380px;
  }
}

.docs-carbonads-container {
  position: relative;
}

.docs-carbonads {
  font-size: .875rem;
  max-width: 360px;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: .25rem;
  overflow: hidden;
}

.carbon-wrap {
  overflow: hidden;
}

.carbon-img {
  clear: left;
  float: left;
  display: block;
}

.carbon-text,
.carbon-poweredby {
  display: block;
  margin-left: 140px;
}

.carbon-text,
.carbon-text:hover,
.carbon-text:focus {
  color: #fff;
  text-decoration: none;
}

.carbon-poweredby,
.carbon-poweredby:hover,
.carbon-poweredby:focus {
  color: #ddd;
  text-decoration: none;
}

@media (min-width: 992px) {
  .docs-carbonads {
    position: absolute;
    right: 0;
    bottom: -1.25rem;
  }
}


/* Content */

.img-container,
.img-preview {
  background-color: #f7f7f7;
  width: 100%;
  text-align: center;
}

.img-container {
  min-height: 200px;
  max-height: 469px;
  margin-bottom: 1rem;
}

@media (min-width: 768px) {
  .img-container {
    min-height: 469px;
  }
}

.img-container > img {
  max-width: 100%;
}

.docs-preview {
  margin-right: -1rem;
}

.img-preview {
  float: left;
  margin-right: .5rem;
  margin-bottom: .5rem;
  overflow: hidden;
}

.img-preview > img {
  max-width: 100%;
}

.preview-lg {
  width: 16rem;
  height: 9rem;
}

.preview-md {
  width: 8rem;
  height: 4.5rem;
}

.preview-sm {
  width: 4rem;
  height: 2.25rem;
}

.preview-xs {
  width: 2rem;
  height: 1.125rem;
  margin-right: 0;
}

.docs-data > .input-group {
  margin-bottom: .5rem;
}

.docs-data > .input-group > label {
  min-width: 5rem;
}

.docs-data > .input-group > span {
  min-width: 3rem;
}

.docs-buttons > .btn,
.docs-buttons > .btn-group,
.docs-buttons > .form-control {
  margin-right: .25rem;
  margin-bottom: .5rem;
}

.docs-toggles > .btn,
.docs-toggles > .btn-group,
.docs-toggles > .dropdown {
  margin-bottom: .5rem;
}

.docs-tooltip {
  display: block;
  margin: -.5rem -.75rem;
  padding: .5rem .75rem;
}

.docs-tooltip > .icon {
  margin: 0 -.25rem;
  vertical-align: top;
}

.tooltip-inner {
  white-space: normal;
}

.btn-upload .tooltip-inner,
.btn-toggle .tooltip-inner {
  white-space: nowrap;
}

.btn-toggle {
  padding: .5rem;
}

.btn-toggle > .docs-tooltip {
  margin: -.5rem;
  padding: .5rem;
}

@media (max-width: 400px) {
  .btn-group-crop {
    margin-right: -1rem!important;
  }

  .btn-group-crop > .btn {
    padding-left: .5rem;
    padding-right: .5rem;
  }

  .btn-group-crop .docs-tooltip {
    margin-left: -.5rem;
    margin-right: -.5rem;
    padding-left: .5rem;
    padding-right: .5rem;
  }
}

.docs-options .dropdown-menu {
  width: 100%;
}

.docs-options .dropdown-menu > li {
  font-size: .875rem;
  padding-left: 1rem;
  padding-right: 1rem;
}

.docs-options .dropdown-menu > li:hover {
  background-color: #f7f7f7;
}

.docs-options .dropdown-menu > li > label {
  display: block;
}

.docs-cropped .modal-body {
  text-align: center;
}

.docs-cropped .modal-body > img,
.docs-cropped .modal-body > canvas {
  max-width: 100%;
}


/* Footer */

.docs-footer {
  font-size: .875rem;
  overflow: hidden;
}

.docs-footer .nav {
  margin-bottom: 1rem;
}

.heart {
  position: relative;
  display: block;
  width: 100%;
  height: 2rem;
  margin-top: 1rem;
  margin-bottom: 0;
  color: #ddd;
  font-size: 1.125rem;
  line-height: 2rem;
  text-align: center;
}

.heart:hover {
  color: #ff4136;
}

.heart:before {
  position: absolute;
  top: 50%;
  right: 0;
  left: 0;
  display: block;
  height: 0;
  border-top: 1px solid #eee;
  content: " ";
}

.heart:after {
  position: relative;
  z-index: 1;
  padding-left: .5rem;
  padding-right: .5rem;
  background-color: #fff;
  content: "â™¥";
}

.thumbnail {
    display: block;
    padding: 4px;
    margin-bottom: 20px;
    line-height: 1.42857143;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    -webkit-transition: border .2s ease-in-out;
    -o-transition: border .2s ease-in-out;
    transition: border .2s ease-in-out;
}
.thumbnail .close{
    position: absolute;
    top: 2px;
    right: 25px;
    float: none;
}
  </style>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4>Выбрать программу:</h4>
            <form>
                <input type="hidden" name="prog_id" id="prog_id" value="" />
                <div class="input-group">
                    <input type="text" data-provide="typeahead" data-items="4" class="form-control" id="prog_name" placeholder="Введите название или id программы">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button">Найти</button>
                    </span>
                </div>
            </form>
            <br />
            <h2 id="progTitle"></h2>
        </div>
    </div>
    <div class="thumbnails row">
            
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <h4>Вырезание фото:</h4>
            <div class="img-container">
                <img id="image" src="" alt="Picture">
            </div>
        </div>
        <div class="col-md-12 col-sm-12 docs-buttons">
            <?/*<h4>Панель управления:</h4>*/?>
            <div class="btn-group">
                <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Увеличить">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Увеличить">
                        <span class="fa fa-search-plus"></span>
                    </span>
                </button>
                <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Уменьшить">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Уменьшить">
                        <span class="fa fa-search-minus"></span>
                    </span>
                </button>
                <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="" data-original-title="Обновить">
                        <span class="fa fa-refresh"></span>
                    </span>
                </button>
            </div>
   
            <div class="btn-group flex-nowrap" data-toggle="buttons">
            <?
            $first_ative = false;
            $arTemplates = \Hawkart\Megatv\CImage::getTemplates();
            foreach($arTemplates as $c=>$arTemplate)
            {
                ?>
                <label class="btn btn-primary img-size <?if(!$first_ative):?> active<?$first_ative = true; endif;?>" data-method="setCropBoxData" 
                data-option="{&quot;width&quot;: <?=$arTemplate["width"]?>, &quot;height&quot;: <?=$arTemplate["height"]?> }" data-class="<?=$c?>"
                data-width="<?=$arTemplate["width"]?>" data-height="<?=$arTemplate["height"]?>">
                    <input type="radio" class="sr-only" name="setCropBoxData" value="{&quot;left&quot;:0, &quot;top&quot;:0, &quot;width&quot;: <?=$arTemplate["width"]?>, &quot;height&quot;: <?=$arTemplate["height"]?> }">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false">
                        <?=$arTemplate["width"]?>&times;<?=$arTemplate["height"]?>
                    </span>
                </label>
                <?
            }
            ?>
            </div>
            
            <div class="btn-group">
                <div class="input-group">
                    <input type="text" class="form-control" id="urlData" placeholder="Ссылка на картинку">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" id="addByUrlData" type="button">Добавить</button>
                    </span>
                </div>
            </div>
            <div class="btn-group">
                <label class="btn btn-primary btn-upload" for="inputImage" title="Загрузить файл">
                    <input type="file" class="sr-only" id="inputImage" name="file" accept=".jpg,.jpeg,.png,.gif,.bmp,.tiff">
                    <span class="docs-tooltip" data-toggle="tooltip" title="Загрузить файл из компьютера">
                        <span class="fa fa-upload"></span>
                    </span>
                </label>
            </div>
            
            <?/*<br />
            <h3>Превью:</h3>
            <div class="docs-preview clearfix">
                <div class="img-preview preview-lg"></div>
            </div>*/?>
            
            <div class="btn-group btn-group-crop">
                <button type="button" class="btn btn-primary" data-method="getCroppedCanvas">
                    <span class="docs-tooltip" data-toggle="tooltip" data-animation="false" title="Сохранить">
                    <span class="fa fa-save"></span> Сохранить
                    </span>
                </button>                
            </div>
            
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js"></script>
<script src="typehead.min.js"></script>
<script src="cropper/dist/cropper.js"></script>
<script>
$(function () {

    'use strict';
    
    var console = window.console || { log: function () {} };
    var URL = window.URL || window.webkitURL;
    var $image = $('#image');
    var $dataX = $('#dataX');
    var $dataY = $('#dataY');
    var $dataHeight = $('#dataHeight');
    var $dataWidth = $('#dataWidth');
    var $dataRotate = $('#dataRotate');
    var $dataScaleX = $('#dataScaleX');
    var $dataScaleY = $('#dataScaleY');
    var options = {
        aspectRatio: "NaN",
        preview: '.img-preview',
        viewMode : 1,
        dragMode: "move",
        crop: function (e) {
            $dataX.val(Math.round(e.x));
            $dataY.val(Math.round(e.y));
            $dataHeight.val(Math.round(e.height));
            $dataWidth.val(Math.round(e.width));
            $dataRotate.val(e.rotate);
            $dataScaleX.val(e.scaleX);
            $dataScaleY.val(e.scaleY);
        },
        autoCropArea: 1,
        strict: false,
        guides: false,
        highlight: false,
        dragCrop: false,
        cropBoxResizable: false
    };
    var originalImageURL = $image.attr('src');
    var uploadedImageURL;
    
    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();

    $image.crossOrigin = 'Anonymous';

    // Cropper
    $image.on({
        ready: function (e) {
            console.log(e.type);
        },
        cropstart: function (e) {
            console.log(e.type, e.action);
        },
        cropmove: function (e) {
            console.log(e.type, e.action);
        },
        cropend: function (e) {
            console.log(e.type, e.action);
        },
        crop: function (e) {
            console.log(e.type, e.x, e.y, e.width, e.height, e.rotate, e.scaleX, e.scaleY);
        },
        zoom: function (e) {
            console.log(e.type, e.ratio);
        }
    }).cropper(options);

    // Buttons
    if (!$.isFunction(document.createElement('canvas').getContext)) 
    {
        $('button[data-method="getCroppedCanvas"]').prop('disabled', true);
    }

    // Methods
    $('.docs-buttons').on('click', '[data-method]', function () 
    {
        var $this = $(this);
        var data = $this.data();
        var $target;
        var result;
        
        if ($image.data('cropper') && data.method) 
        {
            data = $.extend({}, data); // Clone a new one
            
            if (typeof data.target !== 'undefined') 
            {
                $target = $(data.target);
                
                if (typeof data.option === 'undefined') 
                {
                    try {
                        data.option = JSON.parse($target.val());
                    } catch (e) {
                        console.log(e.message);
                    }
                }
            }            
            
            result = $image.cropper(data.method, data.option, data.secondOption);

            //console.log(data.method, data.option, data.secondOption);

            switch (data.method) 
            {
                case 'getCroppedCanvas':
                    if (result) 
                    {                        
                        var cropjpg = result.toDataURL('image/jpeg');
                        $.ajax({
                            type: 'POST',
                            url: '/test/crop/ajax.php',
                            data: {
                                pngimageData: cropjpg,
                                action: "saveCrop",
                                prog_id: $("#prog_id").val(),
                                pkey: $(".img-size.active").data("class"),
                                width: $(".img-size.active").data("width"),
                                height: $(".img-size.active").data("height"),
                            },
                            success: function(output) 
                            {
                                getImagesByProgId($("#prog_id").val());
                            }
                        });
                    }
                break;
                
                case 'destroy':
                    if (uploadedImageURL) 
                    {
                        URL.revokeObjectURL(uploadedImageURL);
                        uploadedImageURL = '';
                        $image.attr('src', originalImageURL);
                    }
                break;
            }
        }
    });

    setTimeout(function()
    {
        $image.cropper("setCropBoxData", {width:288, height: 288});
    }, 200);
    
    $("#addByUrlData").on("click", function()
    {
        var dataUrl = $("#urlData").val();
        
        if(dataUrl!='')
        {
            $.ajax({
                type: 'POST',
                url: '/test/crop/ajax.php',
                data: {
                    url: dataUrl,
                    action: "uploadByUrl"
                },
                success: function(output) 
                {
                    $image.cropper('destroy').attr('src', output).cropper(options);
                    setTimeout(function()
                    {
                        $image.cropper("setCropBoxData", {width:288, height: 288});
                    }, 200);
                }
            });
        }
    });

    // Keyboard
    $(document.body).on('keydown', function (e) 
    {
        if (!$image.data('cropper') || this.scrollTop > 300) 
        {
            return;
        }
        
        switch (e.which) {
            case 37:
            e.preventDefault();
            $image.cropper('move', -1, 0);
            break;
            
            case 38:
            e.preventDefault();
            $image.cropper('move', 0, -1);
            break;
            
            case 39:
            e.preventDefault();
            $image.cropper('move', 1, 0);
            break;
            
            case 40:
            e.preventDefault();
            $image.cropper('move', 0, 1);
            break;
        }
    });


    // Import image
    var $inputImage = $('#inputImage');
    
    if (URL) 
    {
        $inputImage.change(function () 
        {
            var files = this.files;
            var file;
            
            if (!$image.data('cropper')) 
            {
                return;
            }
            
            if (files && files.length) 
            {
                file = files[0];
                
                if (/^image\/\w+$/.test(file.type)) 
                {
                    if (uploadedImageURL) 
                    {
                        URL.revokeObjectURL(uploadedImageURL);
                    }
                    
                    uploadedImageURL = URL.createObjectURL(file);
                    $image.cropper('destroy').attr('src', uploadedImageURL).cropper(options);
                    $inputImage.val('');
                } else {
                    window.alert('Please choose an image file.');
                }
            }
        });
        
    } else {
        $inputImage.prop('disabled', true).parent().addClass('disabled');
    }
    
    function getImagesByProgId(prog_id)
    {
        $.post('/test/crop/ajax.php', {'prog_id':prog_id, 'action':'getImagesByProgId'},
            function (response) 
            {
                $(".thumbnails").html("");
                $.each(response.images, function(i, img)
                {
                    $(".thumbnails").append('<div class="col-sm-6 col-md-3"><div class="thumbnail"><a class="close" href="#">×</a><img src="'+img+'" style="width:100%;"></div></div>');
                });
            },
            'json'
        );
    }
    
    
    //search
    $('#prog_name').typeahead({
        //источник данных
        source: function (query, process) {
            return $.post(
                '/test/crop/ajax.php', 
                {'q':query, 'action' : 'searchProg'},
                function (response) 
                {
                    var data = new Array();
                    $.each(response, function(i, arProg)
                    {
                        data.push(arProg.ID+'_'+"["+arProg.ID+"] "+arProg.UF_TITLE);
                    });
                    return process(data);
                },
                'json'
            );
        }
        //источник данных
        //вывод данных в выпадающем списке
        , highlighter: function(item) {
            var parts = item.split('_');
            parts.shift();
            return parts.join('_');
        }
        //вывод данных в выпадающем списке
        //действие, выполняемое при выборе елемента из списка
        , updater: function(item) {
            var parts = item.split('_');
            var prog_id = parts.shift();
            var prog_name = parts.shift(); 
            //console.log(parts);
            
            getImagesByProgId(prog_id);
            
            $("#progTitle").html(prog_name);
            $("#prog_id").val(prog_id);
            return prog_name;
        }
    });
    
    $(document.body).on("click", ".thumbnail a.close", function(e){
        e.preventDefault();
        
        if($("#prog_id").val()=="")
        {
            alert("Выберите программу");
            return false;
        }
        
        var thumbn = $(this).closest(".thumbnail");
        var img = thumbn.find("img").attr("src");
            
        $.post('/test/crop/ajax.php', {'prog_id':$("#prog_id").val(), 'image':img, 'action':'deleteImage'},
            function (response) 
            {
                //console.log(thumbn);
                thumbn.parent("div").remove();
            },
            'json'
        );
    });
    
});

</script>
</body>
</html>