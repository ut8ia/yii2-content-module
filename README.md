# yii2-slider-module
contnet management functionality 

**installing **
add into composer.json
~~~
 "ut8ia/yii2-contnent-module":"*"
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
 - http://github.com/pendalf89/yii2-filemanager
 
 
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
 
 
 ** usage **

 ~~~

 ~~~