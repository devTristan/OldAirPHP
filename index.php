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
	return classmanager::new_instance(func_get_args());
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

//happily borrowed from codeigniter and ported a bit
$class = s('CI_Router')->fetch_class();
$method = s('CI_Router')->fetch_method();
if (!class_exists($class)
	|| $method == 'controller'
	|| strncmp($method, '_', 1) == 0
	|| in_array(strtolower($method), array_map('strtolower', get_class_methods($class)))
	)
	{
	show_404("{$class}/{$method}");
	}

s('timing')->play('[controller] '.$class.'/'.$method);

// Is this a scaffolding request?
if (s('CI_Router')->scaffolding_request === TRUE)
	{
	s($class)->_ci_scaffolding();
	}
else
	{
	// Is there a "remap" function?
	if (method_exists(s($class), '_remap'))
		{
		s($class)->_remap($method);
		}
	else
		{
		// is_callable() returns TRUE on some versions of PHP 5 for private and protected
		// methods, so we'll use this workaround for consistent behavior
		if (!in_array(strtolower($method), array_map('strtolower', get_class_methods(s($class)))))
			{
			show_404("$class/$method");
			}
		// Call the requested method.
		// Any URI segments present (besides the class/function) will be passed to the method for convenience
		call_user_func_array(array(&$CI, $method), array_slice($URI->rsegments, 2));
		}
	}

s('timing')->pause('[controller] '.$class.'/'.$method);
