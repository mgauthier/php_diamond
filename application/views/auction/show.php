<html>
	<head>
		<title>Auctions</title>
		<?= self::stylesheet('style') ?>
		<?= self::javascript('jquery-1.7.1.min') ?>
		<?= self::javascript('app') ?>
	</head>
	<body>

	<?= self::image('pic.jpg', array("id" => "imgID", "width" => "100px", "class" => "small_pic")) ?>

	This is the Auction List Page
	<br>
	<? self::partial('auction','show'); ?>
	<br>
	<? self::partial('user','default'); ?>
	</body>
</html>