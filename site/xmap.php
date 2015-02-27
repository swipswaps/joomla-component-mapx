<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

JLoader::register('XmapHelper', __DIR__ . '/helpers/xmap.php');

JLoader::register('XmapDisplayer', __DIR__ . '/displayer/displayer.php');
JLoader::register('XmapDisplayerHtml', __DIR__ . '/displayer/html.php');
JLoader::register('XmapDisplayerXml', __DIR__ . '/displayer/xml.php');

$controller = JControllerLegacy::getInstance('Xmap');
$controller->execute(JFactory::getApplication()->input->get('task', 'display'));
$controller->redirect();
