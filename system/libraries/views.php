<?php
class views {
private $data = array();
private $scope = false;
	public function show_view($file,$data = array())
		{
		$this->data = $data;
		unset($data);
		$__folder = substr($file,0,strrpos($file,'/')).'/';
		if ($__folder == '/') {$__folder = '';}
		$files = glob("application/views/$file.*");
		if (!$files || !isset($files[0]))
			{
			show_error('View not found: '.$file);
			}
		$file = substr($files[0],strrpos($files[0],'/')+1);
		unset($files);
		if (count(explode('.',$file)) != 2)
			{
			if (!file_exists(DIR_CACHE.'view_'.$file) || filemtime(DIR_CACHE.'view_'.$file) <= filemtime('application/views/'.$file))
				{
				$parsed = s('parser')->parsefile('application/views/'.$file);
				if ($parsed !== false)
					{
					file_put_contents(DIR_CACHE.'view_'.$file,$parsed);
					$parsed = true;
					}
				}
			else
				{
				$parsed = true;
				}
			}
		else
			{
			$parsed = false;
			}
		foreach ($this->data as $var => $value)
			{
			$$var = $value;
			}
		unset($var);
		unset($value);
		include((($parsed) ? DIR_CACHE.'view_' : DIR_VIEWS).$__folder.$file);
		}
}
