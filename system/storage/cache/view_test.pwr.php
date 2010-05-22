<html>
<head>
<title>Test Page</title>
<style type="text/css">
<?php s('views')->include_view('airphp_style'); ?>
</style>
</head>
<body>
<h1>Yay test!</h1>

<form action="" method="post" enctype="multipart/form-data">
<input type="file" name="filey"/><br/>
<input type="submit" value="GOLOL"/>
</form>

<pre><?php echo print_r(s('input')->files->uploads(),true); ?></pre>

</body>
</html>
