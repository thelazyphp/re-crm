<?php

namespace App\Utils;

use function file_get_html;

use Illuminate\Support\Collection;

class ImagesCrawler
{
    /**
     * @param  string  $url
     * @return \Illuminate\Support\Collection
     */
    public static function crawleImages($url): Collection
    {
        $html = file_get_html($url);

        $images = new Collection();

        foreach ($html->find('a[data-fancybox=gallery-nav]') as $aElem) {
            $images->push('https://bugrealt.by'.$aElem->href);
        }

        return $images;
    }
}
