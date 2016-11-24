<?php

namespace ut8ia\contentmodule\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ut8ia\contentmodule\models\tags".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 */
class Tags extends ActiveRecord
{

    const TYPE_POSITIONING = 1;
    const TYPE_NAVIGATION = 2;
    const TYPE_SEO = 3;

    // tag types
    const TAG_TYPES = [
        1 => 'positioning',
        2 => 'navigation',
        3 => 'seo'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contentmanager_tags';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'type'], 'required'],
            [['type'], 'integer'],
            [['name'], 'string', 'max' => 64]
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
            'type' => Yii::t('app', 'Type'),
        ];
    }

    /**
     * @param $type
     * @param $asArray
     * @param null $limit
     * @return array | $this
     */
    public function getByType($type, $asArray, $limit = null)
    {
        $ans = Tags::find()
            ->where(['=', 'type', $type])
            ->indexBy('id');

        if ($limit) {
            $ans->limit($limit);
        }

        if ($asArray) {
            $ans = $ans->asArray()->all();
            $out = [];
            if (!empty($ans)) {
                foreach ($ans as $ind => $val) {
                    $out[$ind] = $val['name'];
                }
            }
            return $out;
        }

        $ans->all();
        return $ans;
    }


    public static function getTypes()
    {
        return [
            TAGS::TYPE_POSITIONING => Yii::t('app', 'positioning'),
            TAGS::TYPE_NAVIGATION => Yii::t('app', 'navigation'),
            TAGS::TYPE_SEO => Yii::t('app', 'seo')
        ];
    }

    /**
     * @return array
     */
    public static function getSelector()
    {
        return Tags::getTypes();
    }

}
