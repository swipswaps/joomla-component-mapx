<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class XmapControllerSitemaps extends JControllerAdmin
{
    protected $text_prefix = 'COM_XMAP_SITEMAPS';

    public function getModel($name = 'Sitemap', $prefix = 'XmapModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function plugins()
    {
        $this->setRedirect('index.php?option=com_plugins&filter_folder=xmap');
        $this->redirect();
    }
}