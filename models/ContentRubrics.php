<?php

namespace ut8ia\contentmodule\models;

use ut8ia\multylang\models\Lang;
use ut8ia\contentmodule\models\ContentSections;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "contentmanager_rubrics".
 *
 * @property integer $id
 * @property integer $section_id
 * @property string $name_en
 * @property string $name_ru
 */
class ContentRubrics extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contentmanager_rubrics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_en', 'name_ru','section_id'], 'required'],
            ['section_id' ,'integer'],
            [['name_en', 'name_ru'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name_en' => Yii::t('app', 'Name En'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'section_id'=> Yii::t('app', 'Section'),
        ];
    }


    public function getSection(){
        return $this->hasOne(ContentSections::class, ['id' => 'section_id']);
    }

    /**
     * @param $section_id
     * @param null $nullOption
     * @return array|null
     */
    public static function selector($section_id,$nullOption = null)
    {
        $lang_url = Lang::getCurrent()->url;
        $out = ContentRubrics::find()
            ->select('name_' . $lang_url)
            ->where(['=','section_id',$section_id])
            ->indexBy('id')
            ->column();

        if ($nullOption) {
            $out = $nullOption + $out;
        }
        return $out;
    }

}
