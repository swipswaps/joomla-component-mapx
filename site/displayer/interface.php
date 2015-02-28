<?php

/**
 * @author     Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link       http://www.z-index.net
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
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
    // for backward compatibility
}