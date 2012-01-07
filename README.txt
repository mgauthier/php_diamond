
===General

http://www.mysite.com/<controller>/<action>
e.g. http://www.mysite.com/user/show_all

===Setup
1. Modify the file "config.php" to the correct database credentials for your system
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
<link href="stylesheets/style.css" rel="stylesheet" type="text/css"> 
OR 
<?= self::stylesheet('style') ?>

<script type="text/javascript" src="javascripts/app.js"/> 
OR 
<?= self::javascript('app') ?>

<img src="images/pic.jpg" id="imgID" class="small_pic" style="width:100px"/> 
OR 
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

===Controllers

==Declaration
class BigCarController extends DiamondBaseController {

==Methods example
public static function show_GET($params=null) {	
	self::render('show',array("layout" => "default"));
}

public static function add_POST($params)
{		
	$userId = $params["userId"];
	$itemName = $params["itemName"];
	$startPrice = $params["startPrice"];
	
	$response = ItemModel::add($userId,$itemName,$startPrice);
	
	echo $response; 
}

public static function index_POST($params=null) {
	self::redirect('/auction/show');
}

===Models & Tables

1. Create your default model: 

e.g. php scripts/create.php model big_car

class BigCarModel extends DiamondBaseModel {

	public static function table() { return "big_car" } 
	public static function properties() {
		return array( );
	}
}

2.  Add your table properties
class BigCarModel extends DiamondBaseModel {

	public static function table() { return "big_car" } 
	public static function properties() {
		return array( 
			array("name" => "name", "type" => "varchar", "length" => 255),
			array("name" => "year", "type" => "int")
		);
	}
}

3. Create the table
e.g. 
php scripts/create.php table big_car
Created table:big_car

==Examples
ItemModel::find($iid);	
self::find_select("id,budget");
self::find_where("item_id=".$iid." and active=1 for update");
$user = UserModel::find_where("id=".$uid." for update");
AuctionModel::find_select_where("sum(current_price) as blocked_budget", "active=1 and best_bidder_id=".$uid);

self::update_attributes($auction->id, array("active" => 0, "ended_at" => "NOW()")

self::insert(array("item_id"=>$iid, "user_id"=>$uid, "current_price"=>$item->start_price, "active"=>$active, "created_at"=>"NOW()", "started_at"=>$start_time))

if(AuctionModel::exists($aid) && UserModel::exists($uid))

==More Examples
$aid = intval(mysql_real_escape_string($auctionId));
$uid = intval(mysql_real_escape_string($userId));
$amt = intval(mysql_real_escape_string($amount));

mysql_query("SET autocommit = 0");
mysql_query("START TRANSACTION");

//do stuff

if($response["result"] == "success")
	mysql_query("COMMIT");
else
	mysql_query("ROLLBACK");

===Other
PHP Version used in development: PHP 5.3.4


=============
-Mike