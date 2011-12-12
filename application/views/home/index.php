HOME

<?= self::link('/auction/show',"Show Auction") ?>

<div>
<form action="<?= self::link_path('/home/index') ?>" method="POST">

	<input type="text" name="name" value="<?= $params['name'] ? $params['name'] : '' ?>"/>

	<input type="submit" value="Save" />
</form>
</div>