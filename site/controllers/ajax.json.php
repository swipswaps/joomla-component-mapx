<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

class XmapControllerAjax extends JControllerLegacy
{

    public function editElement()
    {
        JSession::checkToken('get') or jexit(JText::_('JINVALID_TOKEN'));

        jimport('joomla.utilities.date');
        jimport('joomla.user.helper');
        $user = JFactory::getUser();
        $groups = array_keys(JUserHelper::getUserGroups($user->get('id')));
        $result = new JRegistry('_default');
        $sitemapId = JREquest::getInt('id');

        if (!$user->authorise('core.edit', 'com_xmap.sitemap.' . $sitemapId)) {
            $result->setValue('result', 'KO');
            $result->setValue('message', 'You are not authorized to perform this action!');
        } else {
            $model = $this->getModel('sitemap');
            if ($model->getItem()) {
                $action = JRequest::getCmd('action', '');
                $uid = JRequest::getCmd('uid', '');
                $itemid = JRequest::getInt('itemid', '');
                switch ($action) {
                    case 'toggleElement':
                        if ($uid && $itemid) {
                            $state = $model->toggleItem($uid, $itemid);
                        }
                        break;
                    case 'changeProperty':
                        $uid = JRequest::getCmd('uid', '');
                        $property = JRequest::getCmd('property', '');
                        $value = JRequest::getCmd('value', '');
                        if ($uid && $itemid && $uid && $property) {
                            $state = $model->chageItemPropery($uid, $itemid, 'xml', $property, $value);
                        }
                        break;
                }
            }
            $result->set('result', 'OK');
            $result->set('state', $state);
            $result->set('message', '');
        }

        echo $result->toString();
    }
}