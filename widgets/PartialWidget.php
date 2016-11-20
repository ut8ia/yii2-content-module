<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 21.08.16
 * Time: 22:35
 */

namespace ut8ia\contentmodule\widgets;

use yii\base\Widget;
use Yii;

class PartialWidget extends Widget
{

    public $items;
    public $wrapClass;
    public $parentId;


    public function run()
    {
        $out = '';
        if (!empty($this->items)) {
            $c = 0;
            foreach ($this->items as $item) {
                $out .= $this->render('SlidePanel',
                    [
                        'item' => $item,
                        'c' => $c,
                        'class' => $this->wrapClass,
                        'parentId' => $this->parentId
                    ]);
                $c++;
            }
        }
        return $out;
    }
//



}
