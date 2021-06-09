<?php

defined('_JEXEC') or die;

/**

 */
class AleantestwFrontHelper extends JHelperContent
{
  /**
  
   */
  public static function ConvertXMLArray($xmlstr)
  {
    $offers = array();
    if ($xmlstr!='') { 
      $xml = new SimpleXMLElement($xmlstr);
      
      $HOP = array();
      foreach ($xml->HotelOptionList->HotelOption as $val ) {
        $HOP[(string)$val['HotelOptionID']] = (string)$val['Special'];
        //AleantestwHelper::vdw((string)$val->HotelOption['HotelOptionID']);
        //AleantestwHelper::vdw($HOP);
      }
      //AleantestwHelper::vdw($HOP);
      
      // DRY!!! На тест оставлю, а так смотреть, как исправлять 
      $RoomCategoryList = array();
      foreach ($xml->RoomCategoryList->RoomCategory as $val ) {
        $RoomCategoryList[(string)$val['RoomCategoryID']] = (string)$val['RoomTypeName'].' '.(string)$val['Name'];
        //AleantestwHelper::vdw($val);
      }
      //AleantestwHelper::vdw($RoomCategoryList);      
      
      //$offers = array();
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
    return $offers;
  }
} 