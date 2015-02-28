<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @var        XmapViewHtml $this
 */

defined('_JEXEC') or die;
?>
    <div id="xmap" class="sitemap<?php echo $this->pageclass_sfx ?>">
        <?php if ($this->params->get('show_page_heading')) : ?>
            <div class="page-header">
                <h1>
                    <?php echo $this->escape($this->params->get('page_heading')); ?>
                </h1>
            </div>
        <?php endif; ?>

        <?php if ($this->item->params->get('showintro', 1) && !empty($this->item->introtex))  : ?>
            <div class="introtext">
                <?php echo $this->item->introtext; ?>
            </div>
        <?php endif; ?>

        <?php echo $this->displayer->printSitemap(); ?>
    </div>
<?php echo $this->loadTemplate('edit');