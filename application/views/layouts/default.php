<html>
<head>
	<title>Diamond PHP</title>

	<?= self::stylesheet('reset') ?>
	<?= self::stylesheet('style') ?>
	<?= self::stylesheet('footer') ?>

	<?= self::javascript('jquery-1.7.1.min') ?>
	<?= self::javascript('app') ?>
</head>
<body>

<div class="wrapper">
<? include_once($layout_view) ?>
</div>

<?= self::partial('layouts','footer') ?>
</body>
</html>