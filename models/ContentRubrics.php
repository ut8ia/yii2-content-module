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
 * @property string $slug
 * @property string name
 * @property string $name_en
 * @property string $name_ru
 * @property string $name_uk
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
            [['name_en', 'name_ru', 'section_id'], 'required'],
            ['section_id', 'integer'],
            [['slug', 'name', 'name_en', 'name_ru', 'name_uk'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'slug' => Yii::t('app', 'Slug'),
            'name' => Yii::t('app', 'Name'),
            'name_en' => Yii::t('app', 'Name En'),
            'name_ru' => Yii::t('app', 'Name Ru'),
            'name_uk' => Yii::t('app', 'Name Uk'),
            'section_id' => Yii::t('app', 'Section'),
        ];
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'ut8ia\contentmodule\behaviors\Slug',
                'in_attribute' => 'name',
                'out_attribute' => 'slug',
                'translit' => true
            ]
        ];
    }

    public function getSection()
    {
        return $this->hasOne(ContentSections::class, ['id' => 'section_id']);
    }

    public function beforeValidate()
    {
        if (isset(Yii::$app->controller->module->sectionId)) {
            $this->section_id = Yii::$app->controller->module->sectionId;
        }
        return parent::beforeValidate();
    }

    /**
     * @param $section_id
     * @param null $nullOption
     * @return array|null
     */
    public static function selector($section_id, $nullOption = null)
    {
        $section_id = ($section_id) ? $section_id : Yii::$app->controller->module->sectionId;
        $out = ContentRubrics::find()
            ->select('name')
            ->where(['=', 'section_id', $section_id])
            ->indexBy('id')
            ->column();

        if ($nullOption) {
            $out = $nullOption + $out;
        }
        return $out;
    }

}
