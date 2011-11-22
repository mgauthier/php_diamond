
===Setup

1. Modify the file "application/db/db.php" to the correct database credentials for your system

2. Create your database and import the database schemas with the file provided in application/db/schema/

3. Copy all files into your web directory


===Running the script

Go to <web_url>/index.php/<action and query string>

e.g. http://www.mysite.com/index.php/add_user?userId=1&budget=1000

===Assumptions

1.  In addition to the specified actions, I've made an assumption that items and auctions are stored separately.  This means that an extra action has been added:

e.g. http://www.mysite.com/index.php/add_auction?itemId=1&userId=1&isActive=1

I've added the specs into the provided specification, you will see it by going to doc/index.html

2.  Each item can only have one active auction running at a given item.  After an auction is finished, another auction can be added for the same item.

3.  Once a user is the highest bidder on an auction, they cannot bid on the same auction again until another user outbids them


===Other

1. Tests: I wrote and ran some unit tests with PHPUnit.  You can find the test script in tests/AuctionTest.php. 
You can also find an html report in tests/report/index.html.  I ran the test with a different database than the
development database.  You can set the test database credentials in tests/AuctionTest.php.

2. PHP Version used in development: PHP 5.3.4



=============
-Mike