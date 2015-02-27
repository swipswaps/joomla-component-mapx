<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class XmapViewSitemap extends JViewLegacy
{
    /**
     * @var JObject
     */
    protected $item;

    /**
     * @var JForm
     */
    protected $form;

    /**
     * @var JObject
     */
    protected $state;

    /**
     * @var array
     */
    protected $menues;

    /**
     * @var JObject
     */
    protected $canDo;

    public function display($tpl = null)
    {
        $this->item = $this->get('Item');
        $this->form = $this->get('Form');
        $this->state = $this->get('State');
        $this->menues = $this->get('Menues');
        $this->canDo = JHelperContent::getActions('com_xmap', 'sitemap');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->handleMenues();

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);

        JToolBarHelper::title(JText::_('XMAP_PAGE_' . ($isNew ? 'ADD_SITEMAP' : 'EDIT_SITEMAP')));

        if ($isNew && $this->canDo->get('core.create')) {
            JToolBarHelper::apply('sitemap.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('sitemap.save', 'JTOOLBAR_SAVE');
            JToolBarHelper::save2new('sitemap.save2new');
        } else if ($this->canDo->get('core.edit') || $this->canDo->get('core.edit.own')) {
            JToolBarHelper::apply('sitemap.apply', 'JTOOLBAR_APPLY');
            JToolBarHelper::save('sitemap.save', 'JTOOLBAR_SAVE');
        }

        if ($this->canDo->get('core.create')) {
            JToolBarHelper::save2copy('sitemap.save2copy');
        }

        JToolBarHelper::cancel('sitemap.cancel', 'JTOOLBAR_CLOSE');
    }

    protected function handleMenues()
    {
        foreach ($this->menues as $menu) {
            $menu->selected = false;
            $menu->ordering = -1;
            $menu->changefreq = 0.5;
            $menu->priority = 'weekly';

            if (isset($this->item->selections[$menu->menutype])) {
                $menu->selected = true;
                $menu->ordering = $this->item->selections[$menu->menutype]['ordering'];
                $menu->priority = $this->item->selections[$menu->menutype]['priority'];
                $menu->changefreq = $this->item->selections[$menu->menutype]['changefreq'];
            }
            unset($menu->description);
        }

        usort($this->menues, array($this, 'sortMenues'));
    }

    protected function sortMenues($a, $b)
    {
        if ($a->ordering == $b->ordering) {
            return 0;
        }

        if ($a->ordering == -1) {
            return 1;
        }

        return ($a->ordering < $b->ordering) ? -1 : 1;
    }
}
