<?php

namespace ut8ia\contentmodule\widgets\partial;

use yii\base\Widget;
use Yii;

/**
 * Class partialWidget
 * @package ut8ia\contentmodule\widgets\partial
 * @property array $items
 * @property string $itemTemplate
 * @property mixed $itemParams;
 * @property string $itemView;
 * @property string $wrapTemplate;
 * @property mixed $wrapParams;
 * @property string $wrapView;
 * @property string $out;
 */
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
            foreach ($this->items as $index => $value) {

                if (!empty($this->itemView)) {

                    $this->out .= $this->renderFile($this->itemView, [
                        'itemIndex' => $index,
                        'itemValue' => $value,
                        'itemParams' => $this->itemParams,
                        'count' => $count
                    ]);

                } elseif (!empty($this->itemTemplate)) {

                    $this->out .= $this->render($this->itemTemplate, [
                        'itemIndex' => $index,
                        'itemValue' => $value,
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
