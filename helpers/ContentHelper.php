<?php

namespace ut8ia\contentmodule\helpers;

use Yii;

class ContentHelper
{
    /**
     * @param string $content
     * @param string $mainId
     * @param string $defaultSrc
     * @return array
     */
    public static function fetchImages($content, $mainId = null ,$defaultSrc = null)
    {
        $mainId = ($mainId) ? $mainId : 'main';
        $images = [];
        $c = 0;

        preg_match_all("#<img(.*?)\/?>#", $content, $matches);
        // extract attributes from each image and place in $images array
        foreach ($matches[1] as $m) {
            preg_match_all("#(\w+)=['\"]{1}([^'\"]*)#", $m, $matches2);

            $tempArray = [];
            foreach ($matches2[1] as $key => $val) {
                $tempArray[$val] = $matches2[2][$key];
            }
            // detect main image
            if (isset($tempArray['id'])) {
                if ($tempArray['id'] == $mainId) {
                    $main = $tempArray;
                }
            }
            $c++;
            $images[$c] = $tempArray;
        }

        // fill default main image
        if (empty($main)) {
            // by first
            if (!empty($images[1])) {
                $main = $images[1];
            } else {
                $main['src'] = ($defaultSrc)?$defaultSrc: 'http://placehold.it/400x250';
                $main['alt'] = 'no image here';
            }
        }
        $out['images'] = $images;
        $out['count'] = $c;
        $out['main'] = $main;
        return $out;
    }


    public static function cleanImages($content){
        return preg_replace("/<img[^>]+\>/i", "", $content);
    }

}
