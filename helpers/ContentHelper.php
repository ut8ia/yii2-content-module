<?php

namespace ut8ia\contentmodule\helpers;

use Yii;

class ContentHelper
{
    public static function fetchImages($content)
    {
        preg_match_all("#<img(.*?)\/?>#", $postContent, $matches);
        // extract attributes from each image and place in $images array
        $images = [];
        foreach ($matches[1] as $m) {
            preg_match_all("#(\w+)=['\"]{1}([^'\"]*)#", $m, $matches2);
            // code below could be a lot neater using array_combine(), but it's php5 only
            $tempArray = [];
            foreach($matches2[1] as $key => $val) {
                $tempArray[$val] = $matches2[2][$key];
            }
//            if ($tempArray['id']=='headImage'){ $headImage=$tempArray['src'];}
//            if ($tempArray['id']=='newsImage'){ $newsImage=$tempArray['src'];}

            return $images;
        }

    }

}
