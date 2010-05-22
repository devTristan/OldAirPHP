<html>
<head>
<title>Welcome to <?php echo FRAMEWORK_NAME.' '.FRAMEWORK_VERSION; ?></title>
<style type="text/css">
<?php s('views')->include_view('airphp_style'); ?>
</style>
</head>
<body>
<h1>Welcome to <?php echo FRAMEWORK_NAME; ?>!</h1>

<p>The page you are looking at is being generated dynamically by <?php echo FRAMEWORK_NAME; ?>.</p>

<p>If you would like to edit this page you'll find it located at:</p>
<code>application/views/welcome_message.php</code>

<p>The corresponding controller for this page is found at:</p>
<code>application/controllers/welcome.php</code>

<p>If you are exploring <?php echo FRAMEWORK_NAME; ?> for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>

<p><br/>Page rendered in <?php echo number_format(s('timing')->elapsed('total'),4); ?> seconds</p>

</body>
</html>
