# yii2-content-module
Content management functionality 
You can easy manage your content.
You can easy display content depends on :
  - section
  - rubric
  - navigation tag
  - positioning tag ( 'header slogan' - for example )
  - id
  - slug
  
Also you can use helpers and widgets.
Author of the content will be added according to *identityClass* configured in your application
**installing **
add into composer.json
~~~
 "ut8ia/yii2-contnent-module":"*"
 ~~~
 
 Apply migrations
 ~~~
 yii migrate --migrationPath=vendor/ut8ia/yii2-content-module/migrations
  yii migrate --migrationPath=vendor/ut8ia/yii2-multylang/migrations
 ~~~
 
 **configuration  **
 add into 'modules' section in your config file 
 
 ~~~php
     'modules' => [
         'content' =>[
             'class' => 'ut8ia\contentmodule\ContnentModule'
         ]
     ],
 ~~~
 
 
 ** requirements **
 - http://github.com/ut8ia/yii2-filemanager
 - http://github.com/ut8ia/yii2-multylang
 
 
 ** recomended **
 - http://github.com/ut8ia/yii2-adminmenu
 section for config menu - for exemple , look adminmenu config
 ~~~php
 
                 'adminmenu' => [
                            'class' => ut8ia\adminmenu\Adminmenu::class,
                            'items' => [
                                0 => [
                                    'name' => 'Весь контент',
                                    'items' => [
                                        1 => [
                                            'module' => 'content',
                                            'controller' => 'content',
                                            'url' => 'index',
                                            'name' => 'Контент'],
                                        2 => [
                                            'module' => 'content',
                                            'controller' => 'tags',
                                            'name' => 'Теги',
                                            'url' => 'index'],
                                        3 => [
                                            'module' => 'content',
                                            'controller' => 'contentrubrics',
                                            'name' => 'Рубріки',
                                            'url' => 'index'],
                                        4 => [
                                            'module' => 'content',
                                            'controller' => 'contentsections',
                                            'name' => 'Секції',
                                            'url' => 'index']
                                    ]
                                ],
                            ]
 ~~~
 
 
 ** config sections **
 add sections to db in admin interface and configure it into modules section 
 each section as a new -= virtual =- content module for each content section that you need.
 For example : content of static site interface , news section , articles .
 Also you can separately config admin form for each section - enable or disable futures.
 Each section has their personal rubricator .
 You can specify layout of your admin interface via 'layoutPath'.
 ~~~php
     'modules' => [
         
         'content' => [
             'class' => 'ut8ia\contentmodule\ContentModule'
         ]
         ,
         'interface_parts' => [
             'class' => 'ut8ia\contentmodule\ContentModule',
             'sectionId' => 1,
             'positioning' => true, // show positioning tags input
             'navigationTags' => true, // show navigation tags input
             'stick' => true, // show sticky checkbox in form
             'multilanguage' =>true // show multylanguiage selector
             'displayFormat' => true // enable format dropdown in form 
             'displayFormats' =>[  // configure possible values
               'simple'=>'simple format', // you can switch your rendering 
               'full'=>'display this content in full template' // depends on this values             
                ]
         ],
         'articles' => [
             'class' => 'ut8ia\contentmodule\ContentModule',
             'sectionId' => 2,
             'layoutPath' => '@frontend/views/layouts',
         ],
         'events' => [
             'class' => 'ut8ia\contentmodule\ContentModule',
             'sectionId' => 3
         ],
 ]
 ~~~
 
 ** usage in views **

 ~~~php
 
 <?php
 
 
 use ut8ia\contentmodule\models\Content;
 use ut8ia\contentmodule\helpers\ContentHelper;
 use ut8ia\contentmodule\assets\ContenthelperAsset;
 use ut8ia\expertsmanager\models\Board;
 use newerton\fancybox\FancyBox;
 
 use yii\helpers\Html;
 
 $this->title = 'About';
 $this->params['breadcrumbs'][] = $this->title;
 
 ContenthelperAsset::register($this);
 
 $sectionId = 3; // your section id
 $rubricId = 4; // your rubric is
 
 //  'breadcrumbs' , 'main content' - navigation tags
?>
 
     <!-- Page Heading/Breadcrumbs -->
     <?php echo Content::contLoc( $sectionId, $rubricId, 'breadcrumbs')->text; ?>
 
     <!-- /content -->
     <?php
     $content = Content::contLoc( $sectionId, $rubricId, 'main content')->text;
     $images = ContentHelper::fetchImages($content);
     $cleanContent = ContentHelper::cleanImages($content);
     $more = ContentHelper::parseMore($cleanContent);

// see another stuff in helper and main content class
 ?>


 ~~~

for example fron-end controller for display "events" with :
 - slug on single event 
 - index with pagination

~~~php
<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use ut8ia\contentmodule\models\Content;
use yii\data\Pagination;
use ut8ia\multylang\models\Lang;


class EventsController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $query = Content::find()
            ->where(['=', 'section_id', 8])
            ->andWhere(['=', '`contentmanager_content`.`lang_id`', Lang::getCurrent()->id])
            ->orderBy('date DESC');

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 3]);
        $models = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
        ]);
        return $this->render('index');
    }

    /**
     * Displays a single Status model.
     * @param string $slug
     * @return mixed
     */
    public function actionSlug($slug)
    {
        $model = Content::find()->where(['slug' => $slug])->one();
        if (!is_null($model)) {

            $latestPosts = Content::find()
                ->where(['section_id' => $model->section_id])
                ->orderBy('date DESC')
                ->all();

            return $this->render('single', [
                'model' => $model,
                'latestPosts' => $latestPosts
            ]);
        } else {
            return $this->redirect('/index');
        }
    }

}

~~~
