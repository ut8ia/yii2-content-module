<?php

namespace ut8ia\contentmodule\models;

use ut8ia\filemanager\behaviors\ImportImagesBehavior;
use Yii;
use yii\db\ActiveRecord;
use ut8ia\filemanager\behaviors\MediafileBehavior;
use ut8ia\multylang\models\Lang;


use ut8ia\contentmodule\helpers\ContentHelper;

/**
 * This is the model class for table "content".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $text
 * @property string $description
 * @property string $source
 * @property string $priority
 * @property string $lang_id
 * @property string $date
 * @property string $rubric_id
 * @property string $author_id
 * @property string $section_id
 * @property string $stick
 * @property string $content_type
 * @property string $display_format
 * @property string $published
 * @property string $publication_date
 * @property string $sort
 */
class Content extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $SystemTags;
    public $NavTags;
    public $SeoTags;

    public $updateTagLinks;

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
            [['text', 'slug', 'description', 'source'], 'string'],
            [['date', 'publication_date', 'author_id', 'SystemTags', 'NavTags', 'stick', 'content_type', 'priority'], 'safe'],
            [['section_id', 'lang_id', 'rubric_id', 'sort'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['display_format'], 'string', 'max' => 32],
            ['published', 'boolean']
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
            'description' => 'Description',
            'source' => 'Source',
            'priority' => 'Priority',
            'date' => 'Date',
            'author_id' => 'Author',
            'rubric_id' => 'Theme',
            'section_id' => 'Section',
            'SystemTags' => Yii::t('main', 'Positioning'),
            'stick' => Yii::t('main', 'it is sticky'),
            'display_format' => Yii::t('main', 'Display format'),
            'published' => Yii::t('main', 'Published'),
            'publication_date' => Yii::t('main', 'Publication date'),
            'sort' => Yii::t('main', 'Sort')
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
                'class' => MediafileBehavior::class,
                'name' => 'content',
                'attributes' => [
                    'text',
                ]
            ],
            'importfiles' => [
                'class' => ImportImagesBehavior::class,
                'altField' => 'name',
                'contentField' => 'text',
                'descriptionField' => 'description',
                'moduleName' => 'filemanager'
            ]
        ];
    }


    public function getAuthor()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'author_id']);
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
        return $this->getLinkedTagsByType($this->id, TAGS::TYPE_POSITIONING, 0, null);
    }


    public function getNavTags()
    {
        return $this->getLinkedTagsByType($this->id, TAGS::TYPE_NAVIGATION, 0, null);
    }

    public function getSeoTags()
    {
        return $this->getLinkedTagsByType($this->id, TAGS::TYPE_SEO, 0, null);
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
     *  if set section - automatic set section_id to model
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
                // author never changes
                unset($this->author_id);
            } else {
                //new records
                // set current user as author
                $this->author_id = \Yii::$app->user->identity->id;

                // if multylang is disabled - set as current lang
                if (!Yii::$app->controller->module->multilanguage) {
                    $this->lang_id = Lang::getDefaultLang()->id;
                }
                // publicate post if it has no custom publication and publication shedule
                if (!Yii::$app->controller->module->publication and !Yii::$app->controller->module->publicationShedule) {
                    $this->published = true;
                    $this->publication_date = date('Y-m-d H:m:s', time());
                }
            }
            return true;
        } else {
            return false;
        }
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->updateTagLinks) {

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
    }

    /**
     * @return static|null
     */
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
            ->orderBy('publication_date DESC')
            ->where(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id]);
        // if set limit - return all 
        $ans = (isset($limit)) ? $ans->limit($limit)->all() : $ans->one();

        return $ans;
    }

    /**
     * @param $rubric_id
     * @param null $limit
     * @param bool $all
     * @param string $order
     * @return $this
     */
    public function byRubric($rubric_id, $limit = null, $all = false, $order = false)
    {
        $order = ($order) ? $order : 'publication_date DESC';
        $ans = Content::find()
            ->where(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id]);
        if (!$all) {
            $ans->andWhere(['=', 'published', true]);
        }
        $ans->orderBy($order);
        $ans = ((int)$limit) ? $ans->limit($limit) : $ans;
        $ans = $ans->all();
        return $ans;
    }

    /**
     * @param $section_id
     * @param null $limit
     * @param bool $all
     * @return $this
     */
    public function bySection($section_id, $limit = null, $all = false)
    {
        $ans = Content::find()
            ->where(['=', 'section_id', $section_id])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id]);
        if (!$all) {
            $ans->andWhere(['=', 'published', true]);
        }
        $ans->orderBy('publication_date DESC');
        $ans = ((int)$limit) ? $ans->limit($limit) : $ans;
        $ans = $ans->all();
        return $ans;

    }


    public function imagesBySection($section_id, $limit = null, $mainOnly = null)
    {
        $ans = Content::find()
            ->where(['=', 'section_id', $section_id])
            ->andWhere(['=', 'published', true])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->orderBy('RAND() ');
        $ans = ((int)$limit) ? $ans->limit($limit) : $ans;
        $items = $ans->all();
        $collection = [];
        if (!empty($items)) {
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
                } else {
                    $imageNum = (int)rand(0, $images['count'] - 1);
                    $collection[$c]['src'] = $images['images'][$imageNum]['src'];
                    $collection[$c]['slug'] = $item['slug'];
                    $collection[$c]['name'] = $item->name;
                }

                $collection[$c]['slug'] = $item['slug'];
                $collection[$c]['name'] = $item['name'];
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
            ->orderBy('publication_date DESC');

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
            ->orderBy('publication_date DESC');
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
            ->orderBy('publication_date DESC')
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

    /**
     * @return array
     */
    public function prioritySelector(){
        return [
            '0.1'=>'0.1',
            '0.2'=>'0.2',
            '0.3'=>'0.3',
            '0.4'=>'0.4',
            '0.5'=>'0.5',
            '0.6'=>'0.6',
            '0.7'=>'0.7',
            '0.8'=>'0.8',
            '0.9'=>'0.9',
            '1.0'=>'1.0',
        ];
    }


    /**
     * @param integer $section_id
     * @param null|integer $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findLatestBySection($section_id, $limit = null, $all = null)
    {
        $ans = Content::find()
            ->where(['section_id' => $section_id]);

        if (!$all) {
            $ans->andWhere(['=', 'published', true]);
        }
        $ans->orderBy('publication_date DESC');
        if ($limit) {
            $ans->limit($limit);
        }
        return $ans->all();
    }

}
