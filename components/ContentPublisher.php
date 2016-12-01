<?php

namespace ut8ia\contentmodule\components;

use yii\base\Object;
use ut8ia\contentmodule\models\Content;

/**
 * Class ContentPublisher
 * @package ut8ia\contentmodule\components
 * @property integer $timstamp
 * @property string $time
 */
class ContentPublisher extends Object
{

    public $timestamp;
    public $time;

    public function init()
    {
        $this->timestamp = time();
        $this->time = date('Y-m-d H:m:s', $this->timestamp);
    }

    public function publishContent()
    {

        $records = Content::find()
            ->where(['!=', 'published', true])
            ->where(['<', 'publication_date', $this->time])
            ->all();

        if (empty($records)) {
            return null;
        }

        foreach ($records as $record) {
            $record->published = true;
            $record->date = $record->publication_date;
            $record->save();
        }
    }

}


?>