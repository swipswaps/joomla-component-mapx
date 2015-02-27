<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

abstract class JHtmlXmap
{
    public static function priorities($name, $value = '0.5', $j)
    {
        $options = array();
        for ($i = 0.1; $i <= 1; $i += 0.1) {
            $options[] = JHTML::_('select.option', $i, $i);
        }
        return JHtml::_('select.genericlist', $options, $name, null, 'value', 'text', $value, $name . $j);
    }

    public static function changefrequency($name, $value = 'weekly', $j)
    {
        $options[] = JHTML::_('select.option', 'hourly', 'hourly');
        $options[] = JHTML::_('select.option', 'daily', 'daily');
        $options[] = JHTML::_('select.option', 'weekly', 'weekly');
        $options[] = JHTML::_('select.option', 'monthly', 'monthly');
        $options[] = JHTML::_('select.option', 'yearly', 'yearly');
        $options[] = JHTML::_('select.option', 'never', 'never');
        return JHtml::_('select.genericlist', $options, $name, null, 'value', 'text', $value, $name . $j);
    }
}