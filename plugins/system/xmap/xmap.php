<?php

/**
 * @author      Guillermo Vargas <guille@vargas.co.cr>
 * @author      Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link        http://www.z-index.net
 * @copyright   (c) 2005 - 2009 Joomla! Vargas. All rights reserved.
 * @copyright   (c) 2015 Branko Wilhelm. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Class plgSystemXmap
 */
class plgSystemXmap extends JPlugin
{
    /**
     * @var bool
     */
    protected $autoloadLanguage = true;

    /**
     * @param Jform $form
     * @param array $data
     *
     * @throws Exception
     */
    public function onContentPrepareForm(Jform $form, $data)
    {
        if (JFactory::getApplication()->isAdmin() && $form->getName() == 'com_menus.item')
        {
            JForm::addFormPath(__DIR__ . '/forms');
            $form->loadFile('menu_item', false);
        }
    }
}