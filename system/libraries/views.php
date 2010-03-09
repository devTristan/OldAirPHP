<?php
class views {
private $data = array();
	public function show_view($__file,$__data = array())
		{
		$__file = 'application/views/'.$__file;
		$__files = glob($__file.'.*');
		if (!$__files || !isset($__files[0]))
			{
			show_error('View not found: '.$__file);
			}
		$__file = $__files[0];
		unset($__files);
		foreach ($__data as $var => $value)
			{
			$$var = $value;
			}
		unset($var);
		unset($value);
		include($__file);
		}
}
