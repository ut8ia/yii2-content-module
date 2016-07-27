<?php

namespace ut8ia\contentmodule\models;

use Yii;
use yii\db\ActiveRecord;
use pendalf89\filemanager\behaviors\MediafileBehavior;
use ut8ia\multylang\models\Lang;
use common\models\User;

/**
 * This is the model class for table "articles".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $text
 * @property string $lang_id
 * @property string $date
 * @property string $rubric_id
 * @property string $author_id
 * @property string $stick
 *
 */
class Articles extends ActiveRecord
{

    /**
     * @inheritdoc
     */
    public $SystemTags;
    public $NavTags;

    public static function tableName()
    {
        return 'articles';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'text', 'date', 'lang_id', 'rubric_id'], 'required'],
            [['text', 'slug', 'stick'], 'string'],
            [['date', 'author_id', 'SystemTags', 'NavTags'], 'safe'],
            ['thumbnail','integer'],
            [['name'], 'string', 'max' => 255]
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
            'stick' => Yii::t('main', 'stick')
        ];
    }

    public function behaviors()
    {
        return [
            'slug' => [
                'class' => 'common\behaviors\Slug',
                'in_attribute' => 'name',
                'out_attribute' => 'slug',
                'translit' => true
            ],
            'mediafile' => [
                'class' => MediafileBehavior::className(),
                'name' => 'article',
                'attributes' => [
                    'thumbnail',
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
        return $this->hasOne(ArticleRubrics::class, ['id' => 'rubric_id']);
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


    public function getDefault()
    {
        return Articles::findOne(0);
    }

    /**
     * @param null $rubric_id
     * @param null $limit
     * @return $this|array|null|\yii\db\ActiveRecord|\yii\db\ActiveRecord[]
     */
    public function getLast($rubric_id = null, $limit = null)
    {
        $ans = Articles::find()
            ->orderBy('date DESC')
            ->where(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`articles`.`lang_id`', Lang::getCurrent()->id]);
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
        $ans = Articles::find()
            ->where(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`articles`.`lang_id`', Lang::getCurrent()->id])
            ->orderBy('date DESC');
        $ans = ((int)$limit) ? $ans->limit($limit) : $ans;
        $ans = $ans->all();
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
        $ans = Articles::find()
            ->from(['tags'])
            ->join('INNER JOIN', 'tags_link', '`tags_link`.`tag_id` = `tags`.`id`')
            ->join('INNER JOIN', 'articles', '`articles`.`id` = `tags_link`.`link_id`')
            ->select('`articles`.*')
            ->where(['=', '`tags`.`name`', $tag])
            ->andWhere(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`tags`.`type`', $tag_type])
            ->andWhere(['=', '`articles`.`lang_id`', Lang::getCurrent()->id])
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
        $ans = Articles::find()
            ->where(['=', 'rubric_id', $rubric_id])
            ->andWhere(['=', '`articles`.`lang_id`', Lang::getCurrent()->id])
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
        $ans = Articles::find()
            ->from(['tags'])
            ->join('INNER JOIN', 'tags_link', '`tags_link`.`tag_id` = `tags`.`id`')
            ->join('INNER JOIN', 'articles', '`articles`.`id` = `tags_link`.`link_id`')
            ->select('`articles`.*')
            ->where(['=', '`tags`.`name`', $tag])
            ->andWhere(['=', '`articles`.`lang_id`', Lang::getCurrent()->id])
            ->orderBy('date DESC')
            ->one();

        if (!isset($ans->id)) {
            $ans = Articles::getDefault();
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
            ->from(['articles'])
            ->join('INNER JOIN', 'tags_link', '`tags_link`.`link_id` = `articles`.`id`')
            ->join('INNER JOIN', 'tags', '`tags`.`id` = `tags_link`.`tag_id`')
            ->select(['`tags`.*'])
            ->indexBy('id')
            ->where(['=', '`articles`.`id`', $article_id])
            ->andWhere(['=', '`tags`.`type`', $tag_type]);
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
     * tags collection for the article
     * @return array
     */
    public function collection()
    {
        return Articles::find()
            ->asArray()
            ->with('tags')
            ->where(['=', '`articles`.`lang_id`', Lang::getCurrent()->id])
            ->all();
    }

    /**
     * @return array
     */
    public function selector()
    {
        return Articles::find()
            ->select('name')
            ->indexBy('id')
            ->column();
    }

}
