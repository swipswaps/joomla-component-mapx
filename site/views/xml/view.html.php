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
    /**
     * @var JObject
     */
    protected $state;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $params;

    /**
     * @var XmapDisplayerXml
     */
    protected $displayer;

    /**
     * @var stdClass
     */
    public $item;

    /**
     * @var array
     */
    public $items;

    /**
     * @var array
     */
    protected $sitemapItems;

    /**
     * @var array
     */
    protected $extensions;

    function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->params = $this->state->get('params');
        $this->item = $this->get('Item');
        $this->items = $this->get('Items');
        $this->sitemapItems = $this->get('SitemapItems');
        $this->extensions = $this->get('Extensions');

        $input = JFactory::getApplication()->input;

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseWarning(500, implode("\n", $errors));
            return false;
        }

        $web = JApplicationCms::getInstance('site');
        $web->clearHeaders();
        $web->setHeader('Content-Type', 'application/xml; charset=UTF-8');
        $web->sendHeaders();

        $this->displayer = new XmapDisplayerXml($this->item, $this->items, $this->extensions);
        $this->displayer->displayAsNews($input->getBool('news'));
        $this->displayer->displayAsImages($input->getBool('images'));
        $this->displayer->displayAsVideos($input->getBool('videos'));
        $this->displayer->setSitemapItems($this->sitemapItems);

        parent::display($tpl);

        $this->getModel()->hit($this->displayer->getCount());

        JFactory::getApplication()->close();
    }
}
