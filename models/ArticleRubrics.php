<?php

namespace ut8ia\contentmodule\models;

use ut8ia\multylang\models\Lang;
use yii\db\ActiveRecord;
use Yii;

/**
 * This is the model class for table "article_rubrics".
 *
 * @property integer $id
 * @property string $name_en
 * @property string $name_ru
 */
class ArticleRubrics extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_rubrics';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name_en', 'name_ru'], 'required'],
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
        ];
    }

    public static function selector($nullOption = null)
    {
        $lang_url = Lang::getCurrent()->url;
        $out = ArticleRubrics::find()
            ->select('name_' . $lang_url)
            ->indexBy('id')
            ->column();

        if ($nullOption) {
            $out = $nullOption + $out;
        }
        return $out;
    }

}
