
===Setup
1. Modify the file "application/db/db.php" to the correct database credentials for your system
2. Copy all files into your web directory


===Scripts

==Create/Delete

php scripts/(create.php|delete.php) (model|controller|mvc) <name>
e.g. script/create.php model big_car
e.g. script/create.php controller big_car
e.g. script/create.php mvc big_car //creates model and controller

php script/(create.php|delete.php) view <controller name> <name>
e.g. script/create.php view big_car show //creates application/views/big_car/show.php

==List
php scripts/list.php (models|controllers|mvc)
//Useful when deleting models or controllers to get the correct name


===Helpers

E.g. 
<?= self::stylesheet('style') ?>
<?= self::javascript('jquery-1.7.1.min') ?>
<?= self::image('pic.jpg', array("id" => "imgID", "width" => "100px", "class" => "small_pic")) ?>

<?= self::link('/',"Home") ?>
<a href="<?= self::link_path('/')?>">MORE HOME</a>

===Rendering Views
Views are meant to be rendered from controllers
render($view, $options=null)
e.g. (In HomeController.php) self::render('index'); //Renders /application/views/home/index.php

* With Layout
e.g. (In HomeController.php) self::render('show',array("layout" => "default")); 
//Renders /application/views/home/show.php with /application/views/layouts/default.php
//In the layout <? include_once($layout_view) ?> is equivalent to :yield in Rails


===Rendering Partials
Partial filenames must begin with _
partial($controller,$view, $options=null) 
e.g. <? self::partial('user','default'); ?> //Renders /application/views/user/_default.php


===Other
PHP Version used in development: PHP 5.3.4


=============
-Mike