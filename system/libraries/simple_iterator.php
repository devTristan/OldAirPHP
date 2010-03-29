<?php
abstract class simple_iterator extends library implements Iterator, Countable {
private $_position = 0;
private $_iterator_array;	
	protected function set_iterator($var)
		{
		if (!$var) {return $this->_iterator_array;}
		$this->_iterator_array = $var;
		}
	public function rewind()
		{
		$var = $this->_iterator_array;
		reset($this->$var);
		}
	public function current()
		{
		$var = $this->_iterator_array;
		return current($this->$var);
		}
	public function key()
		{
		$var = $this->_iterator_array;
		return key($this->$var);
		}
	public function next()
		{
		$var = $this->_iterator_array;
		next($this->$var);
		}
	public function valid()
		{
		$var = $this->_iterator_array;
		return (key($this->$var) !== null);
		}
	public function count()
		{
		$var = $this->_iterator_array;
		return count($this->$var);
		}
}
