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

/**
 * Class XmapDisplayerXml
 */
class XmapDisplayerXml extends XmapDisplayerAbstract
{
    /**
     * @var string
     */
    public $view = 'xml';

    /**
     * @var array
     */
    protected $links = array();

    /**
     * @var array
     */
    protected $sitemapItems = array();

    /**
     * @var SimpleXMLElement
     */
    protected $baseXml = null;

    /**
     * @var string ISO 639 language code for news sitemaps
     */
    protected $defaultLanguage = '*';

    /**
     * @param stdClass $sitemap
     * @param array $items
     * @param array $extensions
     */
    public function __construct(stdClass $sitemap, array &$items, array &$extensions)
    {
        parent::__construct($sitemap, $items, $extensions);

        $languageTag = JFactory::getLanguage()->getTag();

        if (in_array($languageTag, array('zh-cn', 'zh-tw')))
        {
            $this->defaultLanguage = $languageTag;
        } else
        {
            $this->defaultLanguage = XmapHelper::getLanguageCode();
        }
    }

    /**
     * define base xml tree
     *
     * return void
     */
    protected function setBaseXml()
    {
        $this->baseXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><urlset/>');
        $this->baseXml->addAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        if ($this->isType('news'))
        {
            $this->baseXml->addAttribute('xmlns:xmlns:news', 'http://www.google.com/schemas/sitemap-news/0.9');
        }

        if ($this->isType('images'))
        {
            $this->baseXml->addAttribute('xmlns:xmlns:image', 'http://www.google.com/schemas/sitemap-image/1.1');
        }

        if ($this->isType('videos'))
        {
            $this->baseXml->addAttribute('xmlns:xmlns:video', 'http://www.google.com/schemas/sitemap-video/1.1');
        }
    }

    /**
     * @return string
     */
    public function printSitemap()
    {
        foreach ($this->items as $menutype => &$items)
        {
            $this->printMenuTree($items);
        }
        $dom = new DomDocument();
        $dom->loadXML($this->baseXml->asXML());
        $dom->formatOutput = true;

        $this->output = $dom->saveXML();

        return $this->output;
    }

    /**
     * Prints an XML node for the sitemap
     *
     * @param stdClass $node
     *
     * @return bool
     */
    public function printNode(stdClass $node)
    {
        if (is_null($this->baseXml))
        {
            $this->setBaseXml();
        }

        if ($this->isExcluded($node->id, $node->uid))
        {
            return false;
        }

        if ($this->isType('news') && (!isset($node->newsItem) || !$node->newsItem))
        {
            return false;
        }

        if ($this->isType('images') && (!isset($node->images) || empty($node->images)))
        {
            return false;
        }

        if ($this->isType('videos') && (!isset($node->videos) || empty($node->videos)))
        {
            return false;
        }

        if (!isset($node->browserNav))
        {
            $node->browserNav = 0;
        }

        if ($node->browserNav == 3)
        {
            return false;
        }

        if (!isset($node->secure))
        {
            $node->secure = JUri::getInstance()->isSSL();
        }

        if ($node->secure)
        {
            $link = JRoute::_($node->link, true, $node->secure);
        } else
        {
            $link = rtrim(JUri::root(), '/') . JRoute::_($node->link, true);
        }

        // link is already in xml map
        if (isset($this->links[$link]))
        {
            return true;
        }

        $this->count++;
        $this->links[$link] = true;

        if (!isset($node->priority))
        {
            $node->priority = $this->params->get('default_priority', 0.5);
        }

        if (!isset($node->changefreq))
        {
            $node->changefreq = $this->params->get('default_changefreq', 'daily');
        }

        $modified = $this->getValidNodeModified($node);

        // mandatory fields in every type of sitemap
        $url = $this->baseXml->addChild('url');

        $url->addChild('loc', $link);

        /**
         * @see https://support.google.com/webmasters/answer/183668
         */
        if ($this->isType('normal'))
        {
            if ($modified)
            {
                $url->addChild('lastmod', $modified);
            }

            $changefreq = $this->getProperty('changefreq', $node->changefreq, $node->id, 'xml', $node->uid);
            $priority = $this->getProperty('priority', $node->priority, $node->id, 'xml', $node->uid);

            $url->addChild('changefreq', $changefreq);
            $url->addChild('priority', $priority);
        }

        /**
         * @see https://support.google.com/news/publisher/answer/74288
         */
        if ($this->isType('news'))
        {
            if (!isset($node->language) || $node->language == '*')
            {
                $node->language = $this->defaultLanguage;
            }

            $news = $url->addChild('news:news');
            $publication = $news->addChild('news:publication');
            $publication->addChild('news:name', $this->sitemap->params->get('news_publication_name'));
            $publication->addChild('news:language', $node->language);
            $news->addChild('news:publication_date', $modified);
            $news->addChild('news:title', $node->name);

            if (isset($node->keywords) && !empty($node->keywords))
            {
                $news->addChild('news:keywords', $node->keywords);
            }

            if (isset($node->access) && !empty($node->access))
            {
                $news->addChild('news:access', $node->access);
            }

            if (isset($node->genres) && !empty($node->genres))
            {
                $news->addChild('news:genres', $node->genres);
            }

            if (isset($node->stock_tickers) && !empty($node->stock_tickers))
            {
                $news->addChild('news:stock_tickers', $node->stock_tickers);
            }
        }

        /**
         * @see https://support.google.com/webmasters/answer/178636
         */
        if ($this->isType('images'))
        {
            foreach ($node->images as $img)
            {
                $image = $this->baseXml->addChild('image:image');
                $image->addChild('image:loc', $img->src);

                if (isset($img->title) && !empty($img->title))
                {
                    $image->addChild('image:title', $img->title);
                }

                if (isset($img->caption) && !empty($img->caption))
                {
                    $image->addChild('image:caption', $image->caption);
                }

                if (isset($img->geo_location) && !empty($img->geo_location))
                {
                    $image->addChild('image:geo_location', $img->geo_location);
                }

                if (isset($img->license) && !empty($img->license))
                {
                    $image->addChild('image:license', $img->license);
                }
            }
        }

        /**
         * @see https://support.google.com/webmasters/answer/80472
         */
        if ($this->isType('videos'))
        {
            foreach ($node->videos as $vdi)
            {
                $video = $this->baseXml->addChild('video:video');
                $video->addChild('video:thumbnail_loc', $vdi->thumbnail_loc);
                $video->addChild('video:title', $vdi->title);
                $video->addChild('video:description', $vdi->description);

                if (isset($vdi->video) && !empty($vdi->video))
                {
                    $video->addChild('video:video', $vdi->video);
                }

                if (isset($vdi->duration) && !empty($vdi->duration))
                {
                    $video->addChild('video:duration', $vdi->duration);
                }

                if (isset($vdi->duration) && !empty($vdi->duration))
                {
                    $video->addChild('video:duration', $vdi->duration);
                }

                if (isset($vdi->duration) && !empty($vdi->duration))
                {
                    $video->addChild('video:duration', $vdi->duration);
                }
            }
        }

        return true;
    }

    /**
     * @param stdClass $node
     *
     * @return int|null|string
     */
    protected function getValidNodeModified(stdClass $node)
    {
        $nullDate = JFactory::getDbo()->getNullDate();

        $modified = (isset($node->modified) && $node->modified != false && $node->modified != $nullDate && $node->modified != -1) ? $node->modified : null;
        if (!$modified && $this->isType('news'))
        {
            $modified = JFactory::getDate()->toUnix();
        }

        if ($modified && !is_numeric($modified))
        {
            $modified = JFactory::getDate($modified)->toUnix();
        }

        if ($modified)
        {
            $modified = gmdate('Y-m-d\TH:i:s\Z', $modified);
        }

        return $modified;
    }

    /**
     * @todo also check if value on menuitem (added with new system plugin)
     *
     * @param string $property The property that is needed
     * @param string $value The default value if the property is not found
     * @param int $Itemid The menu item id
     * @param string $view (xml / html)
     * @param int $uid Unique id of the element on the sitemap (the id asigned by the extension)
     *
     * @return string
     */
    protected function getProperty($property, $value, $Itemid, $view, $uid)
    {
        if (isset($this->sitemapItems[$view][$Itemid][$uid][$property]))
        {
            return $this->sitemapItems[$view][$Itemid][$uid][$property];
        }

        return $value;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isType($type)
    {
        switch ($type)
        {
            default:
            case 'normal':
                return !$this->isNews && !$this->isImages && !$this->isVideos;
                break;

            case'news':
                return $this->isNews && !$this->isImages && !$this->isVideos;
                break;

            case 'images':
                return $this->isImages && !$this->isNews;
                break;

            case 'videos':
                return $this->isVideos && !$this->isNews;
                break;
        }
    }

    /**
     * @param array $items
     */
    public function setSitemapItems(array $items)
    {
        $this->sitemapItems = $items;
    }

    /**
     * @param bool $val
     */
    public function displayAsNews($val)
    {
        $this->isNews = (bool)$val;
    }

    /**
     * @param bool $val
     */
    public function displayAsImages($val)
    {
        $this->isImages = (bool)$val;
    }

    /**
     * @param bool $val
     */
    public function displayAsVideos($val)
    {
        $this->isVideos = (bool)$val;
    }
}
