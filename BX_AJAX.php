<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

   // подключаем ajax регистрируя расширение
   CJSCore::Init(array('ajax'));
   $sidAjax = 'testAjax';  
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){ //проверка параметра и если он совпадает то выполняет JSON
   $GLOBALS['APPLICATION']->RestartBuffer();
   echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));
   die();
}

?>
<!-- если не попадает в предыдущее условие, то выполняется следующее -->
<div class="group">
   <div id="block"></div >
   <div id="process">wait ... </div >
</div>
<script>
   window.BXDEBUG = true;

function DEMOLoad(){ //функция DEMOLoad()
   BX.hide(BX("block"));   //скрывает элемент block
   BX.show(BX("process")); //показываем элемент process
   BX.ajax.loadJSON( //запускаем JSON и выполняем функцию встроенную с помощью PHP
      '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>', //код PHP вставили для получения URL
      DEMOResponse //вызываем функцию DEMOResponse
   );
}
function DEMOResponse (data){ //функция DEMOResponse
   BX.debug('AJAX-DEMOResponse ', data);
   BX("block").innerHTML = data.RESULT;
   BX.show(BX("block"));   //показываем элемент block
   BX.hide(BX("process")); //скрываем элемент process
   BX.onCustomEvent( //вызываем кастомное событие
      BX(BX("block")),
      'DEMOUpdate'
   );
}

BX.ready(function(){ //по готовности выполнить функцию
   /*
   BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
      window.location.href = window.location.href;
   });
   */
   BX.hide(BX("block"));   //скрываем элемент block
   BX.hide(BX("process")); //скрываем элемент process
   
   //делигацирует на все поле body, но клик выполняется только на элементах с классом css_ajax. Когда кликнут на элемент, выполниться следующая функция.
    BX.bindDelegate(
      document.body, 'click', {className: 'css_ajax' },
      function(e){
         if(!e)
            e = window.event;
         
         DEMOLoad(); //вызываем функцию DEMOLoad()
         return BX.PreventDefault(e); //отменяем действия браузера по умолчанию
      }
   );
   
});

</script>
<div class="css_ajax">click Me</div> <!-- элемент с классом css_ajax, который можем кликнуть -->
<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
