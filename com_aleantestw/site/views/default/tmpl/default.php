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

//AleantestwHelper::vdw($this->objdata);

if (TRUE AND !empty($this->objdata)) {
    $data = '';
    foreach ($this->objdata as $offer) {
     $tmp = implode('</td><td class="">', $offer);
     $tmp = '<tr class=""><td class="">' . $tmp . '</td></tr>';
     $data = $data.$tmp;
    }
    $tmp = implode('</th><th>', array('Начало','Конец','Длительность','Цена','Кол во','Номер','Спец. предложения'));
    $data = '<tr><th>'.$tmp.'</th></tr>'.$data;
    echo '<table class="table table-bordered table-striped">'.$data.'</table>';

} else echo "Пусто!";

//phpinfo();