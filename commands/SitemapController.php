<?php

namespace app\commands;

use yii\console\Controller;
use yii\helpers\Url;
use DOMDocument;

class SitemapController extends Controller
{
    public $defaultAction = 'generate';

    /**
     * Генерация sitemap.xml
     */
    public function actionGenerate()
    {
        $urls = $this->getUrls();
        $xml = $this->generateXml($urls);
        
        $file = \Yii::getAlias('@webroot') . '/sitemap.xml';
        if (file_put_contents($file, $xml)) {
            echo "Sitemap успешно создан в {$file}\n";
        } else {
            echo "Ошибка при создании sitemap\n";
        }
    }

    /**
     * Получение списка URL для sitemap
     */
    private function getUrls()
    {
        return [
            [
                'loc' => Url::base(true) . '/',
                'changefreq' => 'daily',
                'priority' => '1.0',
                'lastmod' => date('Y-m-d')
            ],
            [
                'loc' => Url::to(['/site/about'], true),
                'changefreq' => 'monthly',
                'priority' => '0.8',
                'lastmod' => date('Y-m-d')
            ],
            [
                'loc' => Url::to(['/site/contact'], true),
                'changefreq' => 'monthly',
                'priority' => '0.8',
                'lastmod' => date('Y-m-d')
            ],
            [
                'loc' => Url::to(['/site/registration'], true),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'lastmod' => date('Y-m-d')
            ],
            [
                'loc' => Url::to(['/site/login'], true),
                'changefreq' => 'monthly',
                'priority' => '0.6',
                'lastmod' => date('Y-m-d')
            ]
        ];
    }

    /**
     * Генерация XML
     */
    private function generateXml($urls)
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = true;

        $urlset = $dom->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $urlset->setAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $urlset->setAttribute('xsi:schemaLocation', 'http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
        
        foreach ($urls as $url) {
            $urlElement = $dom->createElement('url');
            
            $loc = $dom->createElement('loc', $url['loc']);
            $urlElement->appendChild($loc);
            
            $changefreq = $dom->createElement('changefreq', $url['changefreq']);
            $urlElement->appendChild($changefreq);
            
            $priority = $dom->createElement('priority', $url['priority']);
            $urlElement->appendChild($priority);
            
            $lastmod = $dom->createElement('lastmod', $url['lastmod']);
            $urlElement->appendChild($lastmod);
            
            $urlset->appendChild($urlElement);
        }
        
        $dom->appendChild($urlset);
        return $dom->saveXML();
    }
} 