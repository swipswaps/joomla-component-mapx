<?php

/**
 * @author     Guillermo Vargas <guille@vargas.co.cr>
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

// Create shortcut to parameters.
$params = $this->item->params;

$live_site = substr_replace(JURI::root(), "", -1, 1);

header('Content-type: text/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
if (($this->item->params->get('beautify_xml', 1) == 1) && !$this->displayer->isNews) {
    $params = '&amp;filter_showtitle=' . JRequest::getBool('filter_showtitle', 0);
    $params .= '&amp;filter_showexcluded=' . JRequest::getBool('filter_showexcluded', 0);
    $params .= (JRequest::getCmd('lang') ? '&amp;lang=' . JRequest::getCmd('lang') : '');
    echo '<?xml-stylesheet type="text/xsl" href="' . $live_site . '/index.php?option=com_xmap&amp;view=xml&amp;layout=xsl&amp;tmpl=component&amp;id=' . $this->item->id . ($this->isImages ? '&amp;images=1' : '') . $params . '"?>' . "\n";
}
?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"<?php echo($this->displayer->isImages ? ' xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"' : ''); ?><?php echo($this->displayer->isNews ? ' xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"' : ''); ?>>

    <?php echo $this->loadTemplate('items'); ?>

</urlset>