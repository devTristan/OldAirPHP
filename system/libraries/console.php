<?php
class console {
	public function log($msg,$type = 'system')
		{
		$msg = '['.date('h:i:sa').'] '.$msg."\n";
		//echo $msg;
		$file = $type.'/'.date('M j, Y').'.log';
		$dirs = explode('/',$file);
		array_pop($dirs);
		$path = 'logs/';
		foreach ($dirs as $dir)
			{
			$path .= $dir;
			@mkdir($path,0700);
			}
		file_put_contents('logs/'.$file,$msg,FILE_APPEND);
		return $this;
		}
	public function error($str = null, $file = null, $line = null, $type = 'Error')
		{
		$message = $type;
		if ($file !== null)
			{
			if ($line !== null)
				{
				$message .= ' on line '.$line.' of '.$file;
				}
			else
				{
				$message .= ' in file '.$file;
				}
			}
		$message .= ': ';
		$message .= ($str !== null) ? $str : 'Unspecified';
		$this->log($message);
		return $this;
		}
	public function fatal_error($str = null, $file = null, $line = null)
		{
		$this->error($str,$file,$line,'Fatal Error');
		die();
		}
	public function warning($str = null ,$file = null, $line = null)
		{
		$this->error($str,$file,$line,'Warning');
		return $this;
		}
}
