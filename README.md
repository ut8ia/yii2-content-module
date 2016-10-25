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
 section for config menu , group 1 - for exemple , look adminmenu config
 ~~~
 
                 1 => [
                     'name' => 'Слайдеры',
                     'items' => [
                         1 => [
                             'module'=>'sliders',
                             'controller' => 'sliders',
                             'url' => 'index',
                             'name' => 'Слайдеры'],
                         2 => [
                             'module'=>'sliders',
                             'controller' => 'slides',
                             'url' => 'index',
                             'name' => 'Слайды'],
                     ]
                 ],
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
 
 ?>

 ~~~
