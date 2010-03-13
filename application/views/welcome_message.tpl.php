<html>
<head>
<title>Welcome to <?php echo s('config')->framework_name.' '.s('config')->framework_version; ?></title>
<link rel="stylesheet" href="<?php echo URL_BASE.'welcome.css'; ?>" type="text/css" media="screen" charset="utf-8" />
</head>
<body>

<h1>Welcome to <?php echo s('config')->framework_name; ?>!</h1>

<p>The page you are looking at is being generated dynamically by <?php echo s('config')->framework_name; ?>.</p>

<p>If you would like to edit this page you'll find it located at:</p>
<code><?php echo DIR_VIEWS; ?>welcome_message.php</code>

<p>The corresponding controller for this page is found at:</p>
<code><?php echo DIR_CONTROLLERS; ?>welcome.php</code>

<p>If you are exploring <?php echo s('config')->framework_name; ?> for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>

<p><br />Page rendered in <?php echo number_format(s('timing')->elapsed('total'),4); ?> seconds</p>

</body>
</html>
