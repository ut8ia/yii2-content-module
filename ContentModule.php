<?php

namespace ut8ia\contentmodule;

use \yii\base\Module;
use ut8ia\contentmodule\models\Content;

/**
 * Class ContentModule
 * @package ut8ia\contentmodule
 * @property integer $sectionId
 * @property boolean $multilanguage
 * @property boolean $description
 * @property boolean $source
 * @property boolean $priority
 * @property boolean $positioning
 * @property boolean $stick
 * @property boolean $navigationTags
 * @property boolean $displayFormat
 * @property array $displayFormats
 * @property boolean $publication
 */
class ContentModule extends Module
{

    public $sectionId;
    public $multilanguage;
    public $description;
    public $source;
    public $priority;
    public $positioning;
    public $contentType;
    public $stick;
    public $navigationTags;
    public $displayFormat;
    public $displayFormats;
    public $publication;
    public $publicationShedule;

    public function init()
    {
        parent::init();
    }

    //** make import of images on all content in called section */
    public function importImagesAllContent()
    {
        $all = Content::find()
            ->where(['section_id' => $this->sectionId])
            ->all();
        foreach ($all as $one) {
            $one->save();
        }

    }


}


?>
