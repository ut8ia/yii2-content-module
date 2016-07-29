<?php

namespace ut8ia\contentmodule\models;

use Yii;

/**
 * This is the model class for table "ut8ia\contentmodule\models\tags_link".
 *
 * @property integer $id
 * @property integer $tag_id
 * @property integer $link_id
 * @property integer $link_type_id
 */
class TagsLink extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contentmanager_tags_link';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tag_id', 'link_id', 'link_type_id'], 'required'],
            [['tag_id', 'link_id', 'link_type_id'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tag_id' => Yii::t('app', 'Tag ID'),
            'link_id' => Yii::t('app', 'Link ID'),
            'link_type_id' => Yii::t('app', 'Link Type ID'),
        ];
    }

    /*
     * insert on or array tags to item
     */

    public function linkTag($item, $tags, $type)
    {
        $tagsLink = new TagsLink();
        $tagsLink->deleteAll(['link_type_id' => $type, 'link_id' => $item]);

        // wrap tags
        if (!is_array($tags)) {
            $tags[] = $tags;
        }

        foreach ($tags as $ind => $val) {
            $tagsLink = new TagsLink();
            $tagsLink->link_type_id = $type;
            $tagsLink->link_id = $item;
            $tagsLink->tag_id = $val;
            try {
                $tagsLink->save();
            } catch (Exception $e) {
                dd($val);
                continue;
            }
            unset($tagsLink);
        }
    }

}
