<?php

namespace ut8ia\contentmodule\widgets\partial;

use yii\base\Widget;
use Yii;

/**
 * Class partialWidget
 * @package ut8ia\contentmodule\widgets\partial
 * @property array $items
 * @property mixed $itemParams;
 * @property string $itemView;
 * @property mixed $wrapParams;
 * @property string $wrapView;
 * @property string $out;
 */
class partialWidget extends Widget
{

    public $items;
    public $itemParams;
    public $itemView;
    public $wrapParams;
    public $wrapView;
    public $out;

    public function run()
    {
        if (empty($this->itemView)) {
        return null;
        }

        $this->out = '';
        if (!empty($this->items)) {
            $count = 0;
            foreach ($this->items as $index => $value) {

                    $this->out .= $this->renderFile($this->itemView, [
                        'itemIndex' => $index,
                        'itemValue' => $value,
                        'itemParams' => $this->itemParams,
                        'count' => $count
                    ]);

                $count++;
            }
        }

        if (!empty($this->wrapView)) {
            $this->out = $this->renderFile($this->wrapView, ['wrapParams' => $this->wrapParams, 'out' => $this->out]);
        }

        return $this->out;
    }
}

?>
