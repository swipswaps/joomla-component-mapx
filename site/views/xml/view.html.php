<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class XmapViewXml extends JViewLegacy
{

    protected $state;

    protected $print;

    protected $_obLevel;

    function display($tpl = null)
    {
        // Initialise variables.
        $app = JFactory::getApplication();
        $this->user = JFactory::getUser();
        $isNewsSitemap = JRequest::getInt('news', 0);
        $this->isImages = JRequest::getInt('images', 0);


        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->canEdit = JFactory::getUser()->authorise('core.admin', 'com_xmap');

        // For now, news sitemaps are not editable
        $this->canEdit = $this->canEdit && !$isNewsSitemap;


        // Get model data.
        $this->items = $this->get('Items');
        $this->sitemapItems = $this->get('SitemapItems');
        $this->extensions = $this->get('Extensions');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }

        // Add router helpers.
        $this->item->slug = $this->item->alias ? ($this->item->id . ':' . $this->item->alias) : $this->item->id;

        $this->item->rlink = JRoute::_('index.php?option=com_xmap&view=xml&id=' . $this->item->slug);


        // Create a shortcut to the paramemters.
        $params = &$this->state->params;

        if (!$this->item->params->get('access-view')) {
            if ($this->user->get('guest')) {
                // Redirect to login
                $uri = JFactory::getURI();
                $app->redirect(
                    'index.php?option=com_users&view=login&return=' . base64_encode($uri),
                    JText::_('Xmap_Error_Login_to_view_sitemap')
                );
                return;
            } else {
                JError::raiseWarning(403, JText::_('Xmap_Error_Not_auth'));
                return;
            }
        }

        // Override the layout.
        if ($layout = $params->get('layout')) {
            $this->setLayout($layout);
        }

        $this->displayer = new XmapDisplayerXml($params, $this->item);

        $this->displayer->setJView($this);

        $this->displayer->isNews = $isNewsSitemap;
        $this->displayer->isImages = $this->isImages;
        $this->displayer->canEdit = $this->canEdit;

        $this->getModel()->hit($this->displayer->getCount());

        parent::display($tpl);
    }
}
