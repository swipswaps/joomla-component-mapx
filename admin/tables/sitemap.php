<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class XmapTableSitemap extends JTable
{
    protected $_jsonEncode = array('params', 'selections');

    public function __construct($db)
    {
        parent::__construct('#__xmap_sitemap', 'id', $db);
    }

    public function bind($array, $ignore = array())
    {
        if (isset($array['selections']) && is_array($array['selections'])) {
            foreach ($array['selections'] as $menutype => $options) {
                if (isset($options['enabled'])) {
                    unset($array['selections'][$menutype]['enabled']);
                } else {
                    unset($array['selections'][$menutype]);
                }
            }
        }

        return parent::bind($array, $ignore);
    }

    /**
     * @todo alias duplication check
     */
    public function check()
    {
        if (empty($this->alias)) {
            $this->alias = $this->title;
        }

        $this->alias = JApplicationHelper::stringURLSafe($this->alias);

        if (trim(str_replace('-', '', $this->alias)) == '') {
            $this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
        }

        return true;
    }

    public function store($updateNulls = false)
    {
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        if ($this->id) {
            $this->modified = $date->toSql();
            $this->modified_by = $user->get('id');
        } else {
            $this->created = $date->toSql();
            $this->created_by = $user->get('id');
        }

        // for old xmap installations
        if (!$this->created_by) {
            $this->created_by = $user->get('id');
        }

        return parent::store($updateNulls);
    }
}
