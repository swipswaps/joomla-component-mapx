<?php

/**
 * @author      Guillermo Vargas <guille@vargas.co.cr>
 * @author      Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link        http://www.z-index.net
 * @copyright   (c) 2005 - 2009 Joomla! Vargas. All rights reserved.
 * @copyright   (c) 2015 Branko Wilhelm. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

interface XmapDisplayerInterface
{
    public function __construct(stdClass $sitemap, array &$items, array &$extensions);

    public function printSitemap();

    public function printNode(stdClass $node);

    public function getCount();

    public function changeLevel($level);
}

interface XmapDisplayer
{
    // for backward compatibility (eg. type hinting in plugins)
}