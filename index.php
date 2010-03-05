<?php
error_reporting(E_ALL);
$overhead_start = microtime(true);
require_once('system/autoload.php');
//some shortcuts
function s($class) //stands for singleton
	{
	return classmanager::single(func_get_args());
	}
function n($class) //stands for new
	{
	return classmanager::spawn(func_get_args());
	}

foreach (s('config')->autoload_classes as $class)
	{
	s($class);
	}
unset($class);
if (!s('config')->enabled)
	{
	s('event')->trigger('site_offline');
	}
s('timing')->play('total')->set('total',microtime(true)-$overhead_start);
s('timing')->pause('overhead')->set('overhead',microtime(true)-$overhead_start);
s('event')->trigger('initialize');

sleep(1);
echo '<pre>'.print_r(s('timing')->table(),true).'</pre>';
