<?php

namespace App\Services\Sitemap;

use Storage;

class SitemapService
{
    protected string $sitemapPath = 'public/sitemap/';
    
    public function create (string $fileName, string $content = '')
    {
        $options = ['visibility' => 'public'];
        $filePath = $this->sitemapPath . $fileName;
        Storage::put($filePath, '', $options);
    }
}