<?php
date_default_timezone_set(@date_default_timezone_get()); //timezone E_STRICT annoying shitfix.

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
s('config'); //keep this here, there needs to be an instance of config

if (!s('config')->enabled)
	{
	s('event')->trigger('site_offline');
	}

s('timing')->play('total');

s('damien')->define_constants();

s('event')->trigger('initialize');


