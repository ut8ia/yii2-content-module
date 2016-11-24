<?php

namespace ut8ia\contentmodule\models;

use Yii;

/**
 * This is the model class for table "contentmanager_sections".
 *
 * @property integer $id
 * @property string $name
 */
class ContentSections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contentmanager_sections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 127],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @param null $nullOption
     * @return array|null
     */
    public static function selector($nullOption = null)
    {
        $out = ContentSections::find()
            ->select('name')
            ->indexBy('id')
            ->column();

        if ($nullOption) {
            $out = $nullOption + $out;
        }
        return $out;
    }


}
