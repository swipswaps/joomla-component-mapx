<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class com_xmapInstallerScript
{
    const JVERSION = 3.4;

    /**
     * @return bool
     */
    public function preflight()
    {
        if (!version_compare(JVERSION, self::JVERSION, '>=')) {
            $link = JHtml::_('link', 'index.php?option=com_joomlaupdate', 'Joomla! ' . self::JVERSION);
            JFactory::getApplication()->enqueueMessage(sprintf('You need %s or newer to install this extension', $link), 'error');

            return false;
        }

        return true;
    }

    /**
     * install all integrated third party plugins and the xmap system plugin
     *
     * @param JAdapterInstance $adapter
     */
    public function install(JAdapterInstance $adapter)
    {
        $path = $adapter->getParent()->getPath('source');

        $folders = JFolder::folders($path . '/plugins/xmap/');

        $plugins = array();

        foreach ($folders as $component) {
            $plugins[$component] = $path . '/plugins/xmap/' . $component;
        }

        // install each third party plugin if component installed
        foreach ($plugins as $component => $plugin) {
            if (JComponentHelper::isInstalled($component)) {
                $installer = new JInstaller;
                $installer->install($plugin);
            }
        }

        // install xmap system plugin
        // TODO implement plugin features in XmapDisplayerHtml
        //$installer = new JInstaller;
        //$installer->install($path . '/plugins/system/xmap/');
    }

    /**
     * @param JAdapterInstance $adapter
     */
    public function update(JAdapterInstance $adapter)
    {
        $this->install($adapter);
    }

    /**
     * uninstall all installed xmap plugins
     * @return bool
     */
    public function uninstall(JAdapterInstance $adapter)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('e.extension_id')
            ->from('#__extensions AS e')
            ->where('e.type = ' . $db->Quote('plugin'))
            ->where('e.folder = ' . $db->quote('xmap') . 'OR (e.element = ' . $db->quote('xmap') . ' AND e.folder = ' . $db->quote('system') . ')');
        $db->setQuery($query);

        try {
            $plugins = $db->loadColumn();
        } catch (RuntimeException $e) {
            return false;
        }

        if (!empty($plugins)) {
            foreach ($plugins as $plugin) {
                $installer = new JInstaller;
                $installer->uninstall('plugin', $plugin);
            }
        }

        return true;
    }

    /**
     * enable all installed xmap plugins
     */
    public function postflight()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->update('#__extensions AS e')
            ->set('e.enabled = ' . $db->quote(1))
            ->where('e.type = ' . $db->quote('plugin'))
            ->where('e.folder = ' . $db->quote('xmap') . 'OR (e.element = ' . $db->quote('xmap') . ' AND e.folder = ' . $db->quote('system') . ')');
        $db->setQuery($query);
        $db->execute();

        $this->postflightDeletePackage();

        $this->postflightDeleteUpdateserver();
    }

    /**
     * delete old package installation set
     */
    protected function postflightDeletePackage()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->delete('#__extensions')
            ->where($db->quoteName('type') . ' = ' . $db->quote('package'))
            ->where($db->quoteName('element') . ' = ' . $db->quote('pkg_xmap'));

        $db->setQuery($query);

        try {
            $db->execute();
        } catch (RuntimeException $e) {
            // do nothing
        }
    }

    /**
     * delete old outdated update server
     */
    protected function postflightDeleteUpdateserver()
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true)
            ->delete('#__update_sites')
            ->where($db->quoteName('name') . ' = ' . $db->quote('Xmap Update Site'));

        $db->setQuery($query);

        try {
            $db->execute();
        } catch (RuntimeException $e) {
            // do nothing
        }
    }
}