<?php

namespace ut8ia\contentmodule;

use \yii\base\Module;

/**
 * Class ContentModule
 * @package ut8ia\contentmodule
 * @property integer $sectionId
 * @property boolean $multilanguage
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
    public $positioning;
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


}


?>
