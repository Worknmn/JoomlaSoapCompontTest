<?php
/**
 * @package     Joomla.Site
 * @subpackage  Components.Aleantestw
 *
 */

// No direct access
defined('_JEXEC') or die;
?>

<div class="blank<?php echo $this->pageclass_sfx; ?>">
<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<h1>
	<?php if ($this->escape($this->params->get('page_heading'))) : ?>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	<?php else : ?>
		<?php echo $this->escape($this->params->get('page_title')); ?>
	<?php endif; ?>
	</h1>
<?php endif; ?>
</div>

<?php

AleantestwHelper::vdw($this->objdata);

// проверяем наличие класса SoapClient
if (FALSE AND class_exists('SoapClient')){
     
    // отключаем кэширование
    ini_set("soap.wsdl_cache_enabled", "0" );  
     
    // подключаемся к серверу
    $client = new SoapClient(
            //"http://extgate.alean.ru:8082/webservice/ewebsvc.dll",
            //"http://extgate.alean.ru:8082/webservice/ewebsvc.dll/wsdl/",
            "http://extgate.alean.ru:8082/webservice/ewebsvc.dll/wsdl/IewsServer",
            array(
            )
        );
    //AleantestwHelper::vdw($client);
    // обращаемся к функции, передаем параметры
    //Login(string ConnectionID, string UserAlias, string Password, string Language, string ProfileID, string ContextXML, unsignedInt Timeout, string SessionID)
    $SessionID = '';
    $result = $client->Login( '', 'Test', 'testik', 'RU', '', '', 900000); //, string SessionID
    if ($result['return'] == 'lrSuccess') {
     //echo 'Login success';
     $SessionID = $result['SessionID'] ;
    }
    //AleantestwHelper::vdw($result);
    
    $clientd = new SoapClient(
            "http://extgate.alean.ru:8082/webservice/ewebsvc.dll/wsdl/ItwsReservationService",
            array(
            )
    );
    //AleantestwHelper::vdw($clientd);
    
	   //GetAbodeReservationTable(string SessionID, TWideStringDynArray TourShortNameArray, TWideStringDynArray HotelTypeShortNameArray, TWideStringDynArray HotelGroupShortNameArray, TWideStringDynArray HotelShortNameArray, TWideStringDynArray RoomTypeShortNameArray, int BaseSeatQuantity, int ExtSeatQuantity, TIntegerDynArray TouristAgeArray, double MinPrice, double MaxPrice, date BeginDateFrom, date BeginDateTill, int DurationFrom, int DurationTill, int MaxVisitCount, int MaxOfferCount, unsignedInt AsyncQueryTimeout, string Banner)
    $resultd = $clientd->GetAbodeReservationTable(
     $SessionID,
     array(),
     array(),
     array(),
     array('Имеретинский'), //TWideStringDynArray HotelShortNameArray, 
     array(), //TWideStringDynArray RoomTypeShortNameArray, 
     2, //2, //int BaseSeatQuantity, 
     -1, //int ExtSeatQuantity, 
     array(-1,-1),//TIntegerDynArray TouristAgeArray, 
     -1,//double MinPrice, 
     -1,//double MaxPrice, 
     '2021-07-01T00:00:00.000',//date BeginDateFrom, 
     '2021-08-15T00:00:00.000',//date BeginDateTill, 
     1, //int DurationFrom, 
     14, //int DurationTill, 
     14, //int MaxVisitCount, 
     null, //int MaxOfferCount, 
     900000,//unsignedInt AsyncQueryTimeout, 
     ''//string Banner
    );
    // $resultd пусто - не найдено
    //AleantestwHelper::vdw($resultd);
    
    
    if ($resultd!='') { 
      $xml = new SimpleXMLElement($resultd);
      
      $HOP = array();
      foreach ($xml->HotelOptionList->HotelOption as $val ) {
        $HOP[(string)$val['HotelOptionID']] = (string)$val['Special'];
        //AleantestwHelper::vdw((string)$val->HotelOption['HotelOptionID']);
        //AleantestwHelper::vdw($HOP);
      }
      //AleantestwHelper::vdw($HOP);
      
      // DRY!!! На тест оставлю, смотреть 
      $RoomCategoryList = array();
      foreach ($xml->RoomCategoryList->RoomCategory as $val ) {
        $RoomCategoryList[(string)$val['RoomCategoryID']] = (string)$val['RoomTypeName'].' '.(string)$val['Name'];
        //AleantestwHelper::vdw($val);
      }
      //AleantestwHelper::vdw($RoomCategoryList);      
      
      $offers = array();
      foreach ($xml->OfferList->Offer as $val ) {
       //AleantestwHelper::vdw($val);
       foreach ($val->VisitList->Visit as $vl) {
        //AleantestwHelper::vdw($vl);
        //AleantestwHelper::vdw($val['RoomCategoryID']);
        $offer = array(
          (string)$vl['Begin'],//начало
          (string)$vl['End'],//конец
          (string)$vl['Amount'],//длительность
          (string)$vl['Cost'],//цена
          (string)$vl['Quantity'],//кол-во путевок
          //(string)$val['RoomCategoryID'],//$RoomCategoryList[(string)$val['RoomCategoryID']],//описание номера
          $RoomCategoryList[(string)$val['RoomCategoryID']],//описание номера
          //(string)$val['HotelOptionID']//$HOP[(string)$val['HotelOptionID']],//опции
          $HOP[(string)$val['HotelOptionID']],//опции
        );
        $offers[] = $offer;
       }
       //$RoomCategoryList[(string)$val['RoomCategoryID']] = (string)$val['RoomTypeName'].' '.(string)$val['Name'];
       //AleantestwHelper::vdw($offer);
      }
      //AleantestwHelper::vdw($offers);
      
    }
    
    $data = '';
    foreach ($offers as $offer) {
     $tmp = implode('</td><td class="">', $offer);
     $tmp = '<tr class=""><td class="">' . $tmp . '</td></tr>';
     $data = $data.$tmp;
    }
    $tmp = implode('</th><th>', array('Начало','Конец','Длительность','Цена','Кол во','Номер','Спец. предложения'));
    $data = '<tr><th>'.$tmp.'</th></tr>'.$data;
    echo '<table class="table table-bordered table-striped">'.$data.'</table>';
    
     
    //вернёт строку длинную
    //$result = $client->GetConnectionList();
    //AleantestwHelper::vdw($result);
    
    
    if ($SessionID != '') $result = $client->Logout($SessionID);
    //AleantestwHelper::vdw($result);
    /*
    
    /**/
} else echo "Включите поддержку SOAP в PHP!";

//phpinfo();