<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

JFormHelper::loadFieldClass('list');

class JFormFieldSitemap extends JFormFieldList
{
    public $type = 'Sitemap';

    public function getOptions()
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('s.id AS value')
            ->select('s.title AS text')
            ->from('#__xmap_sitemap AS s')
            ->where('s.published = ' . $db->quote(1));
        $db->setQuery($query);

        $options = $db->loadObjectList();

        array_unshift($options, JHtml::_('select.option', '', JText::_('JSELECT')));

        return array_merge(parent::getOptions(), $options);
    }

}