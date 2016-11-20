<?php

namespace ut8ia\contentmodule\widgets\partial;

use yii\base\Widget;
use Yii;

class partialWidget extends Widget
{

    public $items;
    public $itemTemplate;
    public $itemParams;
    public $itemView;
    public $wrapTemplate;
    public $wrapParams;
    public $wrapView;
    public $out;

    public function run()
    {
        $this->out = '';
        if (!empty($this->items)) {
            $count = 0;
            foreach ($this->items as $item) {

                if (!empty($this->itemView)) {

                    $this->out .= $this->renderFile($this->itemView, [
                        'item' => $item,
                        'itemParams' => $this->itemParams,
                        'count' => $count
                    ]);

                } elseif (!empty($this->itemTemplate)) {

                    $this->out .= $this->render($this->itemTemplate, [
                        'item' => $item,
                        'itemParams' => $this->itemParams,
                        'count' => $count
                    ]);
                }
                $count++;
            }
        }

        if (!empty($this->wrapView)) {
            $this->out = $this->renderFile($this->wrapView, ['wrapParams' => $this->wrapParams, 'out' => $this->out]);
        } elseif (!empty($this->wrapTemplate)) {
            $this->out = $this->render($this->wrapTemplate, ['wrapParams' => $this->wrapParams, 'out' => $this->out]);
        }

        return $this->out;
    }
}

?>
