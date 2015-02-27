<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @copyright  Copyright (C) 2015 Branko Wilhelm. All rights reserved.
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class plgSystemXmap extends JPlugin
{
    protected $autoloadLanguage = true;

    public function onContentPrepareForm(Jform $form, $data)
    {
        if (JFactory::getApplication()->isAdmin() && $form->getName() == 'com_menus.item') {
            JForm::addFormPath(__DIR__ . '/forms');
            $form->loadFile('menu_item', false);
        }
    }
}