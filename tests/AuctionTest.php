<?php

require_once("../application/models/GenericModel.php");
require_once("../application/models/AuctionModel.php");
require_once("../application/models/UserModel.php");
require_once("../application/models/ItemModel.php");
require_once("../application/models/BidModel.php");

require_once("../application/controllers/AuctionController.php");
require_once("../application/controllers/UserController.php");
require_once("../application/controllers/ItemController.php");

//used for access to generic model
require_once("models/TestUserModel.php");
require_once("models/TestAuctionModel.php");
require_once("models/TestItemModel.php");

class AuctionTest extends PHPUnit_Framework_TestCase
{

	protected function setUp()
    {
        if (!extension_loaded('mysql')) {
            $this->markTestSkipped(
              'The MySQL extension is not available.'
            );
        }
        else
        {
        	$host = '127.0.0.1';
			$username = 'root';
			$pwd = '';
			$db = 'speeddate_test';
		
			$con = mysql_connect($host,$username,$pwd);
			
			if(!$con)
				return false;
			else
				mysql_select_db($db, $con);
			
			return true;
        }

    }
    
    public function testSnapshot1()
    {
    	$response = AuctionModel::snapshot();    	
    	$this->assertEquals("success", $response["result"]);
    }
    
    public function testAddUser()
    {
    	mysql_query("TRUNCATE TABLE users;");
    	
    	//test invalid input
    	$response = UserModel::add("a","1000");
    	$this->assertEquals("error", $response["result"], $response["error"]);
    	$response = UserModel::add(1,"a");
    	$this->assertEquals("error", $response["result"], $response["error"]);
    	$response = UserModel::add(0,"1000");
    	$this->assertEquals("error", $response["result"], $response["error"]);
    	$response = UserModel::add(1,"0");
    	$this->assertEquals("error", $response["result"], $response["error"]);

    	    	    	
    	for($i=1; $i<10; $i++)
    	{
	    	$response = UserModel::add($i,"1000");
	    	$this->assertEquals("success", $response["result"]);
		}
		
		$response = UserModel::add("1","1000");
    	$this->assertEquals("error", $response["result"], $response["error"]);

    }
    
    /**
     * @depends testAddUser
     */
    public function testAddItem()
    {
    	mysql_query("TRUNCATE TABLE items;");
    	
    	//test invalid input
        $response = ItemModel::add("a","box1","1");
    	$this->assertEquals("error", $response["result"]);
        $response = ItemModel::add("1","box1","a");
    	$this->assertEquals("error", $response["result"]);
        $response = ItemModel::add(0,"box1","5");
    	$this->assertEquals("error", $response["result"]);
        $response = ItemModel::add("1","box1",0);
    	$this->assertEquals("error", $response["result"]);

    		   
    	$response = ItemModel::add("1","box1","1");
    	$this->assertEquals("success", $response["result"]);
    	
    	$response = ItemModel::add("2","box1","2");
    	$this->assertEquals("error", $response["result"]);
    	
    	$response = ItemModel::add("2","box2","20");
    	$this->assertEquals("success", $response["result"]);
    	
    	$response = ItemModel::add("2","box3","50");
    	$this->assertEquals("success", $response["result"]);
    }
    
    /**
     * @depends testAddItem
     */
    public function testAddAuction()
    {
       	mysql_query("TRUNCATE TABLE auctions;");   
       	
       	//test invalid input
        $response = ItemModel::add("a","1","1");
    	$this->assertEquals("error", $response["result"]);
        $response = ItemModel::add("1","1","a");
    	$this->assertEquals("error", $response["result"]);
        $response = ItemModel::add("0","1","1");
    	$this->assertEquals("error", $response["result"]);
        $response = ItemModel::add("1","1","0");
    	$this->assertEquals("error", $response["result"]);
    	
       	
    	$response = AuctionModel::add("1", "1", "1");
    	$this->assertEquals("success", $response["result"], $response["result"]);

    	$response = AuctionModel::add("1", "1", "1");
    	$this->assertEquals("error", $response["result"], $response["error"]);

		$response = AuctionModel::add("1", "1", "0");
    	$this->assertEquals("error", $response["result"], $response["error"]);
    	
		$response = AuctionModel::add("1", "2", "1");
    	$this->assertEquals("error", $response["result"], $response["error"]);
    	
    	$response = AuctionModel::add("2", "2", "1");
    	$this->assertEquals("success", $response["result"]);
    	
    	//mysql_query("COMMIT");
    }
    
    /**
     * @depends testAddAuction
     */
	public function testSnapshot2()
    {
    	$response = AuctionModel::snapshot();    	
    	$this->assertEquals("success", $response["result"]);
    }
    
    /**
     * @depends testAddAuction
     */
    public function testBids()
    {	
	    mysql_query("TRUNCATE TABLE bids;");   	
	    
    	$userId1 = "1";
    	$userId2 = "2";
    	$itemId = "1";
    
    	//test invalid input
       	$response = AuctionModel::bid("0",$itemId,"5");    
		$this->assertEquals("error", $response["result"]);
       	$response = AuctionModel::bid($userId1,"5000","5");    
		$this->assertEquals("error", $response["result"]);
       	$response = AuctionModel::bid($userId1,$itemId,0);    
		$this->assertEquals("error", $response["result"]);
       	$response = AuctionModel::bid($userId1,$itemId,"a");    
		$this->assertEquals("error", $response["result"]);

    	
    	$user1 = TestUserModel::find($userId1);
    	$initialBudget1 = $user1->budget;
    	
    	
		$response = AuctionModel::bid($userId1,$itemId,"5");    
		$this->assertEquals("success", $response["result"]);
		
		//invalid amount
		$response = AuctionModel::bid($userId1,$itemId,"5");    
		$this->assertEquals("error", $response["result"], $response["error"]);
		
		$user1 = TestUserModel::find($userId1);
		$this->assertEquals(intval($user1->budget), 995, $user1->budget);

		$response = AuctionModel::bid($userId2,$itemId,"6");    
		$this->assertEquals("success", $response["result"]);

		$user1 = TestUserModel::find($userId1);
		$user2 = TestUserModel::find($userId2);
		$this->assertEquals(intval($user1->budget), 1000, $user1->budget);
		$this->assertEquals(intval($user2->budget), 994, $user2->budget);
		
		$response = AuctionModel::bid($userId1,$itemId,"70000");    
		$this->assertEquals("error", $response["result"], $response["error"]);
		
		$auction = TestAuctionModel::find(2);
		$this->assertEquals($auction->best_bidder_id, NULL);
		
		
    }
      
    public function testAuctionFinish()
    {
    	$response = AuctionModel::finish("1");
    	$this->assertEquals("success", $response["result"]);
    	
    	//add a new auction for the same item
    	$response = AuctionModel::add("1","1","1");
    	$this->assertEquals("success", $response["result"]);
    }
    
    public function testSnapshot3()
    {
    	$response = AuctionModel::snapshot();    	
    	$this->assertEquals("success", $response["result"]);
    }
    
    public function testControllersSimple()
    {
    	UserController::add(array("userId"=>"10","budget"=>"2000"));
    	$user = TestUserModel::find("10");
    	$this->assertNotEquals($user, NULL);
    	
    	ItemController::add(array("userId"=>"10","itemName"=>"my_item","startPrice"=>"100"));
    	$item = TestItemModel::find_where("name='my_item'");
    	$this->assertNotEquals($item, NULL);
    	
    	AuctionController::add(array("itemId"=>"4", "userId"=>"1", "isActive"=>"1"));
    	$auction = TestAuctionModel::find_where("item_id=4 and user_id=1");
    	$this->assertNotEquals($auction, NULL);    	
    	
    	$response = AuctionController::bid(array("itemId"=>"4", "userId"=>"1", "amount"=>"10"));
    	$this->assertEquals("error", $response["result"]);
    	$response = AuctionController::bid(array("itemId"=>"4", "userId"=>"1", "amount"=>"101"));
    	$this->assertEquals("success", $response["result"]);
    	    	    	    	
    	$response = AuctionController::finish("4");
    	$this->assertEquals("success", $response["result"]);
    	$auction = TestAuctionModel::find_where("item_id=4 and user_id=1");
    	$this->assertEquals($auction->active, 0);
    	$this->assertEquals($auction->best_bidder_id, 1);
    	
    	
    	
    	$response = AuctionController::snapshot(NULL);
    	$this->assertEquals("success", $response["result"]);
    }

    
}
?>