<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @var        XmapViewSitemaps $this
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$trashed = $this->state->get('filter.state') == -2 ? true : false;
$sortFields = $this->getSortFields();

JFactory::getDocument()->addStyleDeclaration('#toolbar-power-cord{float:right;}@media(max-width: 767px){#toolbar-power-cord{float:none;}}');
?>
<script type="text/javascript">
    Joomla.orderTable = function () {
        table = document.getElementById("sortTable");
        direction = document.getElementById("directionTable");
        order = table.options[table.selectedIndex].value;
        if (order != '<?php echo $listOrder; ?>') {
            dirn = 'asc';
        }
        else {
            dirn = direction.options[direction.selectedIndex].value;
        }
        Joomla.tableOrdering(order, dirn, '');
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_xmap&view=sitemaps'); ?>" method="post" name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <?php else : ?>
        <div id="j-main-container">
            <?php endif; ?>
            <div id="filter-bar" class="btn-toolbar">
                <div class="filter-search btn-group pull-left">
                    <label for="filter_search" class="element-invisible"><?php echo JText::_('JSEARCH_FILTER'); ?></label>
                    <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('COM_PLUGINS_SEARCH_IN_TITLE'); ?>" />
                </div>
                <div class="btn-group pull-left">
                    <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                    <button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="limit" class="element-invisible"><?php echo JText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
                    <?php echo $this->pagination->getLimitBox(); ?>
                </div>
                <div class="btn-group pull-right hidden-phone">
                    <label for="directionTable" class="element-invisible"><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></label>
                    <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JFIELD_ORDERING_DESC'); ?></option>
                        <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_ASCENDING'); ?></option>
                        <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('JGLOBAL_ORDER_DESCENDING'); ?></option>
                    </select>
                </div>
                <div class="btn-group pull-right">
                    <label for="sortTable" class="element-invisible"><?php echo JText::_('JGLOBAL_SORT_BY'); ?></label>
                    <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                        <option value=""><?php echo JText::_('JGLOBAL_SORT_BY'); ?></option>
                        <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder); ?>
                    </select>
                </div>
            </div>
            <div class="clearfix"></div>

            <?php if (empty($this->items)) : ?>
                <div class="alert alert-no-items">
                    <?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
                </div>
            <?php else : ?>

                <table class="table table-striped" id="sitemapList">
                    <thead>
                    <tr>
                        <th width="1%" class="hidden-phone">
                            <?php echo JHtml::_('grid.checkall'); ?>
                        </th>
                        <th width="1%" style="min-width:55px" class="nowrap center">
                            <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                        </th>
                        <th class="title">
                            <?php echo JHtml::_('grid.sort', 'COM_XMAP_HEADING_SITEMAP', 'a.title', $listDirn, $listOrder); ?>
                        </th>
                        <th width="5%" class="nowrap hidden-phone">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
                        </th>
                        <th width="10%" class="nowrap center hidden-phone">
                            <?php echo JText::_('COM_XMAP_HEADING_HTML_STATS'); ?><br />
                            (<?php echo JText::_('COM_XMAP_HEADING_NUM_LINKS') . ' / ' . JText::_('COM_XMAP_HEADING_NUM_HITS') . ' / ' . JText::_('COM_XMAP_HEADING_LAST_VISIT'); ?>)
                        </th>
                        <th width="10%" class="nowrap center hidden-phone">
                            <?php echo JText::_('COM_XMAP_HEADING_XML_STATS'); ?><br />
                            (<?php echo JText::_('COM_XMAP_HEADING_NUM_LINKS') . ' / ' . JText::_('COM_XMAP_HEADING_NUM_HITS') . ' / ' . JText::_('COM_XMAP_HEADING_LAST_VISIT'); ?>)
                        </th>
                        <th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($this->items as $i => $item) :
                        $canEdit = $user->authorise('core.edit', 'com_xmap.sitemap.' . $item->id);
                        $canChange = $user->authorise('core.edit.state', 'com_xmap.component');
                        $canEditOwn = $user->authorise('core.edit.own', 'com_xmap.sitemap.' . $item->id) && $item->created_by == $userId;
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="center hidden-phone">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td class="center">
                                <div class="btn-group">
                                    <?php echo JHtml::_('jgrid.published', $item->published, $i, 'sitemaps.', $canChange, 'cb'); ?>
                                    <?php
                                    // Create dropdown items
                                    $action = $trashed ? 'untrash' : 'trash';
                                    JHtml::_('actionsdropdown.' . $action, 'cb' . $i, 'sitemaps');

                                    // Render dropdown list
                                    echo JHtml::_('actionsdropdown.render', $this->escape($item->title));
                                    ?>
                                </div>
                            </td>
                            <td class="title">
                                <div class="pull-left">
                                    <?php if ($canEdit || $canEditOwn) : ?>
                                        <a href="<?php echo JRoute::_('index.php?option=com_xmap&task=sitemap.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
                                            <?php echo $this->escape($item->title); ?>
                                        </a>
                                    <?php else : ?>
                                        <span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
                                    <?php endif; ?>
                                    <br />
                                    <small><?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias));?></small>
                                </div>

                                <?php if ($item->published): ?>
                                    <div class="pull-right">
                                        <a href="<?php echo '../index.php?option=com_xmap&amp;view=html&amp;id=' . $item->id; ?>" target="_blank" class="btn-micro btn-success hasTooltip" title="<?php echo JText::_('COM_XMAP_HTML_LINK_TOOLTIP', true); ?>"><?php echo JText::_('COM_XMAP_HTML_LINK'); ?></a>
                                        <a href="<?php echo '../index.php?option=com_xmap&amp;view=xml&amp;id=' . $item->id; ?>" target="_blank" class="btn-micro btn-primary hasTooltip" title="<?php echo JText::_('COM_XMAP_XML_LINK_TOOLTIP', true); ?>"><?php echo JText::_('COM_XMAP_XML_LINK'); ?></a>
                                        <a href="<?php echo '../index.php?option=com_xmap&amp;view=xml&amp;news=1&amp;id=' . $item->id; ?>" target="_blank" class="btn-micro btn-success hasTooltip" title="<?php echo JText::_('COM_XMAP_NEWS_LINK_TOOLTIP', true); ?>"><?php echo JText::_('COM_XMAP_NEWS_LINK'); ?></a>
                                        <a href="<?php echo '../index.php?option=com_xmap&amp;view=xml&amp;images=1&amp;id=' . $item->id; ?>" target="_blank" class="btn-micro btn-success hasTooltip" title="<?php echo JText::_('COM_XMAP_IMAGES_LINK_TOOLTIP', true); ?>"><?php echo JText::_('COM_XMAP_IMAGES_LINK'); ?></a>
                                        <a href="<?php echo '../index.php?option=com_xmap&amp;view=xml&amp;videos=1&amp;id=' . $item->id; ?>" target="_blank" class="btn-micro btn-success hasTooltip" title="<?php echo JText::_('COM_XMAP_VIDEOS_LINK_TOOLTIP', true); ?>"><?php echo JText::_('COM_XMAP_VIDEOS_LINK'); ?></a>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo $this->escape($item->access_level); ?>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo $item->count_html . ' / ' . $item->views_html; ?>
                                <div class="small"><?php echo XmapHelper::getLastVisitDate($item->lastvisit_html); ?></div>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo $item->count_xml . ' / ' . $item->views_xml; ?>
                                <div class="small"><?php echo XmapHelper::getLastVisitDate($item->lastvisit_xml); ?></div>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo (int)$item->id; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

            <?php echo $this->pagination->getListFooter(); ?>

            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" value="0" />
            <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
            <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
</form>
