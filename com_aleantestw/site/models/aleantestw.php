<?php
/**
 * @package     Joomla.Site
 * @subpackage  Components.Aleantestw
 *
 * Пока всё в одном, по идее Soap надо проверить или вытолкнуть в контроллер, чтобы сразу была остановка, если его нет
 */

// No direct access
defined('_JEXEC') or die;

// ComponentnameModelViewname                                                                              
class AleantestwModelAleantestw extends 
//JModelItem
JModelLegacy 
//JModelBase - инициализировать надо
{
	/**
	 * @var
	 */
	protected $SessionID = '';
 
 protected $client = null;
 
 protected $isSoap = FALSE;
 
 protected $iserver = "http://extgate.alean.ru:8082/webservice/ewebsvc.dll/wsdl/IewsServer";
 
 
 function __construct($config=array()) {
  
  // проверяем наличие класса SoapClient
  if (TRUE AND class_exists('SoapClient')){
   $this->isSoap = TRUE;
  } else {
    // no soap
    $this->setError("Включите поддержку SOAP в PHP!");
  }
  //parent::__construct(null);
  parent::__construct($config);
 } 

	/**
	 * 
	 */
	private function getSessionID() {
 
  if (!$this->isSoap) return $this->SessionID;
    
  if ( $this->SessionID==''){
       
      // отключаем кэширование
      //ini_set("soap.wsdl_cache_enabled", "0" );  
      $this->setClient($this->iserver, array('cache_wsdl' => WSDL_CACHE_NONE
      //, "trace" => 1 доступны свойства    SoapClient->__getLastRequest, SoapClient->__getLastRequestHeaders, SoapClient->__getLastResponse и SoapClient->__getLastResponseHeaders. 
      //, "exception" => 0  будут ли SOAP-ошибки бросать исключения типа SoapFault. 
      //, classmap может использоваться для сопоставления некоторых WSDL-типов с PHP-классами. Опция должна представлять собой массив, в качестве ключей которого указаны WSDL-типы, а в качестве значений - имена классов PHP.
      )); 
      //AleantestwHelper::vdw($client);
      // обращаемся к функции, передаем параметры
      //Login(string ConnectionID, string UserAlias, string Password, string Language, string ProfileID, string ContextXML, unsignedInt Timeout, string SessionID)
      $result = $this->client->Login( '', 'Test', 'testik', 'RU', '', '', 900000); //, string SessionID
      if ($result['return'] == 'lrSuccess') {
       //echo 'Login success';
       $this->SessionID = $result['SessionID'] ;
      }
  }
  
  return $this->SessionID;  
 }
 
 private function setClient($url, $a=array()) {
  if (!$this->isSoap) return $this->client;
          
  // подключаемся к серверу
  $this->client = new SoapClient($url, $a); 
 }
 
 private function Logout () {
  if (!$this->isSoap) return FALSE;
  if ( $this->SessionID!='') {
   $this->setClient($this->iserver); 
   $result = $this->client->Logout($this->SessionID);
   return $result; 
  } else return FALSE; 
 }
 
 public function getData($hsn=array('Имеретинский')) {
  if (!$this->isSoap) return FALSE;
  
  $this->getSessionID();
  
  if ( $this->SessionID=='') { return FALSE; }
  try {
    $this->setClient("http://extgate.alean.ru:8082/webservice/ewebsvc.dll/wsdl/ItwsReservationService");
    
     //GetAbodeReservationTable(string SessionID, TWideStringDynArray TourShortNameArray, TWideStringDynArray HotelTypeShortNameArray, TWideStringDynArray HotelGroupShortNameArray, TWideStringDynArray HotelShortNameArray, TWideStringDynArray RoomTypeShortNameArray, int BaseSeatQuantity, int ExtSeatQuantity, TIntegerDynArray TouristAgeArray, double MinPrice, double MaxPrice, date BeginDateFrom, date BeginDateTill, int DurationFrom, int DurationTill, int MaxVisitCount, int MaxOfferCount, unsignedInt AsyncQueryTimeout, string Banner)
    $resultd = $this->client->GetAbodeReservationTable(
     $this->SessionID,
     array(),
     array(),
     array(),
     $hsn, //TWideStringDynArray HotelShortNameArray, 
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
  } catch (Exception $e) {   //"exception" => 1  будут SOAP-ошибки типа SoapFault.
    $this->setError($e->getMessage());
  }
  
  $this->Logout();
  
  return $resultd;
   
 }
 
}