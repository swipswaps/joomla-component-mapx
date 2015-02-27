<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

abstract class XmapHelper
{
    public static function getStateOptions()
    {
        return array(
            JHtml::_('select.option', '1', JText::_('JPUBLISHED')),
            JHtml::_('select.option', '0', JText::_('JUNPUBLISHED')),
            JHtml::_('select.option', '-2', JText::_('JTRASHED')),
            JHtml::_('select.option', '*', JText::_('JALL'))
        );
    }
}