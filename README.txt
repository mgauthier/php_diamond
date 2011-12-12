
===Setup

1. Modify the file "application/db/db.php" to the correct database credentials for your system

2. Create your database and import the database schemas with the file provided in application/db/schema/

3. Copy all files into your web directory


===Running the script

Go to <web_url>/<class>/<action and query string>

e.g. http://www.mysite.com/user/add?userId=1&budget=1000

===Helpers

E.g. 
<?= self::stylesheet('style') ?>
<?= self::javascript('jquery-1.7.1.min') ?>
<?= self::image('pic.jpg', array("id" => "imgID", "width" => "100px", "class" => "small_pic")) ?>

===Other

This is a work in progress.....

2. PHP Version used in development: PHP 5.3.4



=============
-Mike