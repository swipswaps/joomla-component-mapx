<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

domix::err();

if (!JFactory::getUser()->authorise('core.manage', 'com_xmap')) {
    return JFactory::getApplication()->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
}

JForm::addFieldPath(__DIR__ . '/models/fields');

JLoader::register('XmapHelper', __DIR__ . '/helpers/xmap.php');

$controller = JControllerLegacy::getInstance('Xmap');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();