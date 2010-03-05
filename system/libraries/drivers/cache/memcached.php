<?php
class cache_memcached extends driver {
private $link;
	public function __construct()
		{
		$this->link = memcache_connect('localhost', 11211);
		}
	public function get($item)
		{
		return memcache_get($this->link, $item);
		}
	public function set($item,$value)
		{
		memcache_set($this->link, $item, $value, 0, 2592000);
		return true;
		}
	public function exists($item)
		{
		return ($this->get($item)) ? true : false;
		}
	public function remove($item)
		{
		memcache_delete($this->link, $item);
		}
	public function clear()
		{
		memcache_flush($this->link);
		}
}
