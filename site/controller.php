<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class XmapController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = false)
    {
        $urlparams = array('id' => 'INT', 'itemid' => 'INT', 'uid' => 'CMD', 'action' => 'CMD', 'property' => 'CMD', 'value' => 'CMD');

        $viewName = JFactory::getApplication()->input->getCmd('view');
        $viewType = JFactory::getDocument()->getType();

        $view = $this->getView($viewName, $viewType);

        $view->setModel($this->getModel('Sitemap'), true);

        return parent::display($cachable, $urlparams);
    }
}