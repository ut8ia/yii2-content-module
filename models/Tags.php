<?php

namespace ut8ia\contentmodule\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tags".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 */
class Tags extends ActiveRecord
{

    // tag types
    const TAG_TYPES = [
        1 => 'system',
        2 => 'navigation',
        3 => 'seo'
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tags';
    }


    public static $instance = null;

    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
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


    /**
     * @return array
     */
    public function getSelector()
    {
        return Tags::TAG_TYPES;
    }

}
