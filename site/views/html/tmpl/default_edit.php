<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @var        XmapViewHtml $this
 */

defined('_JEXEC') or die;

if ($this->canEdit) {
    JHtml::_('bootstrap.framework');
    JHtml::_('bootstrap.loadCss');
    JHtml::_('bootstrap.tooltip');
    ?>
    <script>
        jQuery(document).ready(function ($) {
            $('#xmap i[class*="icon-"]').css('cursor', 'pointer').on('click', function () {
                var $this = $(this);

                $.ajax({
                    url: '<?php echo JUri::root(); ?>index.php',
                    dataType: 'json',
                    type: 'POST',
                    data: {
                        option: 'com_xmap',
                        format: 'json',
                        task: 'sitemap.editElement',
                        action: 'toggleElement',
                        id: $this.data('id'),
                        uid: $this.data('uid'),
                        itemid: $this.data('itemid'),
                        lang: '<?php echo XmapHelper::getLanguageCode(); ?>',
                        '<?php echo JSession::getFormToken(); ?>': 1
                    },
                    success: function (response) {
                        $this.removeClass('icon-remove-sign icon-ok-sign');
                        $this.attr('title', response.message).tooltip('fixTitle').tooltip('show');

                        if (response.success) {
                            $this.addClass(response.data.state ? 'icon-ok-sign' : 'icon-remove-sign');
                        } else {
                            $this.addClass('icon-circle-question-mark');
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        $this.addClass('icon-circle-question-mark');
                    }
                });
            });
        });
    </script>
<?php } ?>