<?php

namespace ut8ia\contentmodule\models;

use Yii;
use yii\db\ActiveRecord;
use ut8ia\filemanager\behaviors\MediafileBehavior;
use ut8ia\multylang\models\Lang;
use ut8ia\contentmodule\models\ContentRubrics;
use ut8ia\contentmodule\models\ContentSections;
use ut8ia\contentmodule\models\Tags;
use ut8ia\contentmodule\models\TagsLink;
use common\models\User;

use ut8ia\contentmodule\helpers\ContentHelper;

/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $text
 * @property string $lang_id
 * @property string $date
 * @property string $rubric_id
 * @property string $author_id
 * @property string $section_id
 * @property string $stick
 * @property string $content_type
 * @property string $display_format
 */
class Content extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $SystemTags;
    public $NavTags;

    public static function tableName()
    {
        return 'contentmanager_content';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'text', 'rubric_id', 'section_id'], 'required'],
            [['text', 'slug'], 'string'],
            [['date', 'author_id', 'SystemTags', 'NavTags', 'stick', 'content_type'], 'safe'],
            [['section_id', 'lang_id', 'rubric_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['display_format'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'slug',
            'text' => 'Text',
            'date' => 'Date',
            'author_id' => 'Author',
            'rubric_id' => 'Theme',
            'section_id' => 'Section',
            'SystemTags' => Yii::t('main', 'Positioning'),
            'stick' => Yii::t('main', 'it is sticky'),
            'display_format'=> Yii::t('main', 'Display format'),
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
            ],
            'mediafile' => [
                'class' => MediafileBehavior::className(),
                'name' => 'content',
                'attributes' => [
                    'text',
                ]
            ]
        ];
    }


    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public function getRubric()
    {
        return $this->hasOne(ContentRubrics::class, ['id' => 'rubric_id']);
    }


    public function getSection()
    {
        return $this->hasOne(ContentSections::class, ['id' => 'section_id']);
    }


    public function getLanguage()
    {
        return $this->hasOne(Lang::class, ['id' => 'lang_id']);
    }

    public function getArticleTags()
    {
        return $this->hasMany(TagsLink::class, ['link_id' => 'id']);
    }

    public function getTags()
    {
        return $this->hasMany(Tags::class, ['id' => 'tag_id'])
            ->viaTable(TagsLink::tableName(), ['link_id' => 'id']);
    }

    public function getSystemTags()
    {
        return $this->getLinkedTagsByType($this->id, 1, 0, null);
    }


    public function getNavTags()
    {
        return $this->getLinkedTagsByType($this->id, 2, 0, null);
    }


    public function getContentTypes()
    {
        return [
            'html' => 'html',
            'text' => 'text',
            'javascript' => 'javascript'
        ];
    }

    /**
     * @return if set section - automatic set section_id to model
     */
    public function beforeValidate()
    {
        if (isset(Yii::$app->controller->module->sectionId)) {
            $this->section_id = Yii::$app->controller->module->sectionId;
        }
        return parent::beforeValidate();
    }

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            // set author_id only for new records
            if ($this->id > 0) {
                unset($this->author_id);
            } else {
                // set current user as author
                $this->author_id = \Yii::$app->user->identity->id;
            }

            // if multylang is disabled - set as current lang
            if (!Yii::$app->controller->module->multilanguage) {
                $this->lang_id = Lang::getDefaultLang()->id;
            }

            return true;
        } else {
            return false;
        }
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if (is_array($this->NavTags) and is_array($this->SystemTags)) {
            $tags = array_merge($this->NavTags, $this->SystemTags);
        } elseif (!is_array($this->NavTags)) {
            $tags = $this->SystemTags;
        } else {
            $tags = $this->NavTags;
        }
        $tagsLink = new TagsLink();
        $tagsLink->linkTag($this->id, $tags, 1);
    }


    public static function getDefault()
    {
        return Content::findOne(0);
    }

    /**
     * @param null $rubric_id
     * @param null $limit
     * @return $this|array|null|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]
     */
    public function getLast($rubric_id = null, $limit = null)
    {
        $ans = Content::find()
            ->orderBy('date DESC')
            ->where(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id]);
        // if set limit - return all 
        $ans = (isset($limit)) ? $ans->limit($limit)->all() : $ans->one();

        return $ans;
    }

    /**
     * @param $rubric_id
     * @param null $limit
     * @return $this
     */
    public function byRubric($rubric_id, $limit = null)
    {
        $ans = Content::find()
            ->where(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->orderBy('date DESC');
        $ans = ((int)$limit) ? $ans->limit($limit) : $ans;
        $ans = $ans->all();
        return $ans;
    }

    /**
     * @param $section_id
     * @param null $limit
     * @return $this
     */
    public function bySection($section_id, $limit = null)
    {
        $ans = Content::find()
            ->where(['=', 'section_id', $section_id])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->orderBy('date DESC');
        $ans = ((int)$limit) ? $ans->limit($limit) : $ans;
        $ans = $ans->all();
        return $ans;

    }


    public function imagesBySection($section_id, $limit = null, $mainOnly = null)
    {
        $ans = Content::find()
            ->where(['=', 'section_id', $section_id])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->orderBy('RAND() ');
        $ans = ((int)$limit) ? $ans->limit($limit) : $ans;
        $items = $ans->all();

        if (!empty($items)) {
            $collection = [];
            $c = 0;
            foreach ($items as $item) {
                $images = ContentHelper::fetchImages($item->text);
                //skip if content has no images
                if (!$images['count']) {
                    continue;
                }

                // fetch image - main or random
                if ($mainOnly) {
                    $collection[$c]['src'] = $images['main']['src'];
                    $collection[$c]['slug'] = $item['slug'];
                } else {
                    $imageNum = (int)rand(0, $images['count'] - 1);
                    $collection[$c]['src'] = $images['images'][$imageNum]['src'];
                    $collection[$c]['slug'] = $item['slug'];
                }

                $c++;
                if ($c == $limit) {
                    break;
                }
            }
        }
        return $collection;
    }


    /**
     * @param $section_id
     * @param $rubric_id
     * @param $tag
     * @return array|null|ActiveRecord
     */
    public static function contLoc($section_id, $rubric_id, $tag)
    {
        return Content::contOne($section_id, $rubric_id, $tag, 1);
    }

    /**
     * @param $section_id
     * @param $rubric_id
     * @param $tag
     * @param $tag_type
     * @return array|null|ActiveRecord
     */
    public static function contOne($section_id, $rubric_id, $tag, $tag_type)
    {
        $ans = Content::find()
            ->from(['contentmanager_tags'])
            ->join('INNER JOIN', 'contentmanager_tags_link', '`contentmanager_tags_link`.`tag_id` = `contentmanager_tags`.`id`')
            ->join('INNER JOIN', 'contentmanager_content', '`contentmanager_content`.`id` = `contentmanager_tags_link`.`link_id`')
            ->select('`contentmanager_content`.*')
            ->where(['=', '`contentmanager_tags`.`name`', $tag])
            ->andWhere(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`contentmanager_tags`.`type`', $tag_type])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->andWhere(['=', '`contentmanager_content`.`section_id`', $section_id])
            ->one();
        if (!isset($ans->id)) {
            $ans = Content::getDefault();
        }
        return $ans;
    }

    /**
     * @param $rubric_id
     * @param $tag
     * @param $tag_type
     * @param null $limit
     * @return $this
     */
    public function byRubricTag($rubric_id, $tag, $tag_type, $limit = null)
    {
        $ans = Content::find()
            ->from(['contentmanager_tags'])
            ->join('INNER JOIN', 'contentmanager_tags_link', '`contentmanager_tags_link`.`tag_id` = `contentmanager_tags`.`id`')
            ->join('INNER JOIN', 'contentmanager_content', '`contentmanager_content`.`id` = `contentmanager_tags_link`.`link_id`')
            ->select('`contentmanager_content`.*')
            ->where(['=', '`contentmanager_tags`.`name`', $tag])
            ->andWhere(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`contentmanager_tags`.`type`', $tag_type])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->orderBy('date DESC');

        $ans = ((int)$limit) ? $ans->limit($limit) : $ans;
        $ans = $ans->all();
        return $ans;
    }

    /**
     * @param $rubric_id
     * @param null $limit
     * @return $this
     */
    public function StickByRubric($rubric_id, $limit = null)
    {
        $ans = Content::find()
            ->where(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->andWhere(['=', 'stick', 'true'])
            ->orderBy('date DESC');
        $ans = ((int)$limit) ? $ans->limit($limit) : $ans;
        $ans = $ans->all();
        return $ans;
    }

    /**
     * @param $tag
     * @return array|null|\yii\db\ActiveRecord|static
     */
    public function byTag($tag)
    {
        $ans = Content::find()
            ->from(['contentmanager_tags'])
            ->join('INNER JOIN', 'contentmanager_tags_link', '`contentmanager_tags_link`.`tag_id` = `contentmanager_tags`.`id`')
            ->join('INNER JOIN', 'content', '`contentmanager_content`.`id` = `contentmanager_tags_link`.`link_id`')
            ->select('`contentmanager_content`.*')
            ->where(['=', '`contentmanager_tags`.`name`', $tag])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->orderBy('date DESC')
            ->one();

        if (!isset($ans->id)) {
            $ans = Content::getDefault();
        }
        return $ans;
    }


    /**
     * @param $article_id
     * @param $tag_type
     * @param $asArray
     * @param null $limit
     * @return $this|string
     */
    public function getLinkedTagsByType($article_id, $tag_type, $asArray, $limit = null)
    {
        $out = "";
        $ans = Tags::find()
            ->from(['contentmanager_content'])
            ->join('INNER JOIN', 'contentmanager_tags_link', '`contentmanager_tags_link`.`link_id` = `contentmanager_content`.`id`')
            ->join('INNER JOIN', 'contentmanager_tags', '`contentmanager_tags`.`id` = `contentmanager_tags_link`.`tag_id`')
            ->select(['`contentmanager_tags`.*'])
            ->indexBy('id')
            ->where(['=', '`contentmanager_content`.`id`', $article_id])
            ->andWhere(['=', '`contentmanager_tags`.`type`', $tag_type]);
        if ((int)$limit) {
            $ans->limit($limit);
        }
        if ($asArray) {
            $ans->asArray();
        }
        $ans = $ans->all();

        if ($asArray) {
            foreach ($ans as $ind => $val) {
                $out[$ind] = $val['name'];
            }
            return $out;
        }
        return $ans;
    }

    /**
     * tags collection for the content
     * @return array
     */
    public function collection()
    {
        return Content::find()
            ->asArray()
            ->with('tags')
            ->where(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->all();
    }

    /**
     * @return array
     */
    public function selector()
    {
        return Content::find()
            ->select('name')
            ->indexBy('id')
            ->column();
    }

}
