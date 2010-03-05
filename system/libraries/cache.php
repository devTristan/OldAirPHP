<?php
class cache extends has_drivers {
private $methods = array();
private $prefix;
	public function __construct($dummy1,$dummy2)
		{
		$args = func_get_args();
		$this->prefix = 'damien_'.array_shift($args).'_';
		if (count($args) == 1 && is_array($args[0])) {$args = $args[0];}
		$this->methods = $args;
		}
	public function __get($item)
		{
		$item = $this->prefix.$item;
		$old = $item;
		$lineup = array();
		foreach ($this->methods as $method)
			{
			$value = $this->driver($method)->get($item);
			if ($value !== false)
				{
				foreach ($lineup as $method)
					{
					$this->driver($method)->set($item,$value);
					}
				return $value;
				}
			$lineup[] = $method;
			}
		return false;
		}
	public function __set($item,$value)
		{
		$item = $this->prefix.$item;
		$old = $item;
		foreach ($this->methods as $method)
			{
			if (!$this->driver($method)->set($item,$value))
				{
				break;
				}
			}
		}
	public function __isset($item)
		{
		$item = $this->prefix.$item;
		foreach ($this->methods as $method)
			{
			$value = $this->driver($method)->exists($item);
			if ($value !== false)
				{
				return $value;
				}
			}
		return false;
		}
	public function __unset($item)
		{
		$item = $this->prefix.$item;
		foreach ($this->methods as $method)
			{
			$this->driver($method)->remove($item);
			}
		}
	public function clear()
		{
		foreach ($this->methods as $method)
			{
			$this->driver($method)->clear();
			}
		}
}
