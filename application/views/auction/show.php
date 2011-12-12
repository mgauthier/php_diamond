<?= self::image('pic.jpg', array("id" => "imgID", "width" => "100px", "class" => "small_pic")) ?>
<br>
This is the Auction List Page
<br>
<? 
self::partial('auction','show'); 
?>
<br>
<? 
self::partial('user','default'); 
?>	