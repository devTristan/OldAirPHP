<?php
class cache_file {
private $prefix = 'cache/cache_';
private $delayed = array();
private $lastwrite = 0;
private $writedelay = 0;
	public function __destruct()
		{
		$this->process_delayed();
		}
	public function get($item)
		{
		if (!file_exists($this->prefix.$item))
			{
			return false;
			}
		return unserialize(file_get_contents($this->prefix.$item));
		}
	public function set($item,$value)
		{
		$this->delayed[$item] = $value;
		$this->process_delayed();
		return true;
		}
	private function process_delayed()
		{
		if (time()-$this->lastwrite >= $this->writedelay || 1)
			{
			$this->lastwrite = time();
			foreach ($this->delayed as $item => $value)
				{
				file_put_contents($this->prefix.$item,serialize($value));
				}
			}
		}
	public function exists($item)
		{
		return file_exists($this->prefix.$item);
		}
	public function remove($item)
		{
		unset($this->delayed[$item]);
		if (!$this->exists($item)) {return 0;}
		unlink($this->prefix.$item);
		}
	public function clear()
		{
		$this->delayed = array();
		foreach (glob($this->prefix.'*') as $file)
			{
			unlink($file);
			}
		}
}
