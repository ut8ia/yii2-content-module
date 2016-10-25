# yii2-slider-module
contnet management functionality 

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
 
 ~~~
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
 ~~~
 
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
 each section as a new -= virtual =- content module .
 ~~~
     'modules' => [
         
         'content' => [
             'class' => 'ut8ia\contentmodule\ContentModule'
         ]
         ,
         'interface_parts' => [
             'class' => 'ut8ia\contentmodule\ContentModule',
             'sectionId' => 1
         ],
         'articles' => [
             'class' => 'ut8ia\contentmodule\ContentModule',
             'sectionId' => 2
         ],
         'events' => [
             'class' => 'ut8ia\contentmodule\ContentModule',
             'sectionId' => 3
         ],
 ]
 ~~~
 
 ** usage in views **

 ~~~
 
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
