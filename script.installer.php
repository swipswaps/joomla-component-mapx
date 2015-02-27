<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class com_xmapInstallerScript
{
    const JVERSION = 3.3;

    /**
     * @todo remove pkg_xmap manifest from previous xmap installations
     * @todo reduce version numbers from previous integrated third party plugins so the new versions can be installed
     * @return bool
     * @throws Exception
     */
    public function preflight()
    {
        if (!version_compare(JVERSION, self::JVERSION, '>=')) {
            $link = JHtml::_('link', 'index.php?option=com_joomlaupdate', 'Joomla! ' . self::JVERSION);
            JFactory::getApplication()->enqueueMessage(sprintf('You need %s or newer to install this extension', $link), 'error');

            return false;
        }
    }

    /**
     * @todo add JComponentHelper::isEnabled() for third party plugins!
     * @param JAdapterInstance $adapter
     */
    public function install(JAdapterInstance $adapter)
    {
        $db = JFactory::getDbo();

        // list all integrated xmap plugin types
        $folders = JFolder::folders($adapter->getParent()->getPath('source') . '/plugins/', '.', false, true);

        $plugins = array();

        // put all integrated xmap plugins into one array
        foreach ($folders as $folder) {
            $plugins = array_merge($plugins, JFolder::folders($folder, '.', false, true));
        }

        if (!empty($plugins)) {
            // install every xmap plugin in single steps
            foreach ($plugins as $plugin) {
                $installer = new JInstaller;
                $installer->install($plugin);
            }

            // enable all installed xmap plugins
            $query = $db->getQuery(true)
                ->update('#__extensions AS e')
                ->set('e.enabled = ' . $db->quote(1))
                ->where('e.type = ' . $db->quote('plugin'))
                ->where('AND (e.folder = ' . $db->quote('xmap') . 'OR (e.element = ' . $db->quote('xmap') . ' AND e.folder = ' . $db->quote('system') . ')');
            $db->setQuery($query);
            $db->execute();
        }
    }

    public function update(JAdapterInstance $adapter)
    {
        $this->install($adapter);
    }

    /**
     * @todo test this step
     * @param JAdapterInstance $adapter
     */
    public function uninstall(JAdapterInstance $adapter)
    {
        $db = JFactory::getDbo();

        // uninstall all xmap plugins
        $query = $db->getQuery(true)
            ->select('e.extension_id')
            ->from('#__extensions AS e')
            ->where('e.type = ' . $db->Quote('plugin'))
            ->where('AND (e.folder = ' . $db->quote('xmap') . 'OR (e.element = ' . $db->quote('xmap') . ' AND e.folder = ' . $db->quote('system') . ')');
        $db->setQuery($query);
        $plugins = $db->loadColumn();

        if (!empty($plugins)) {
            foreach ($plugins as $plugin) {
                $installer = new JInstaller;
                $installer->uninstall('plugin', $plugin);
            }
        }
    }

    /**
     * @todo did we need this?
     */
    public function postflight()
    {

    }
}