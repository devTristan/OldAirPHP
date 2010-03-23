<?php
error_reporting(E_ALL);
$overhead_start = microtime(true);
require_once('system/autoload.php');
require_once('system/functions.php');
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

s('timing')->play('CI Load');
//happily borrowed from codeigniter and ported a bit
$class = s('CI_Router')->fetch_class();
$method = s('CI_Router')->fetch_method();
s('timing')->pause('CI Load');

s('output')->start()->header('Content-Type','text/html');
__autoload('controller_'.$class);
s('timing')->play('[controller] '.$class.'/'.$method);

if (s('CI_Router')->scaffolding_request === true)
	{
	s($class)->_ci_scaffolding();
	}
else
	{
	if (method_exists(s('controller_'.$class), '_remap'))
		{
		s($class)->_remap($method);
		}
	else
		{
		if (!in_array(strtolower($method), array_map('strtolower', get_class_methods(s('controller_'.$class)))))
			{
			show_404("$class/$method");
			}
		call_user_func_array(array(s('controller_'.$class), $method), array_slice(s('CI_URI')->rsegments, 2));
		}
	}

s('timing')->pause('[controller] '.$class.'/'.$method);
s('event')->trigger('shutdown');
s('output')->end();
