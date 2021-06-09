<?php

defined('_JEXEC') or die;

/**

 */
class AleantestwHelper extends JHelperContent
{
  /**
  
   */
  public static function vdw($v)
  {
    echo '<pre>';
    var_dump($v);
    echo '</pre>';
  }
} 