<?php
class output {
private $headers = array();
	public function start()
		{
		ob_start();
		return $this;
		}
	public function end()
		{
		foreach ($this->headers as $field => $value)
			{
			header((($field == 'Status') ? '' : $field.': ') . $value);
			}
		ob_end_flush();
		return $this;
		}
	public function header($field,$value = null)
		{
		if ($value === null)
			{
			$value = $field;
			$field = 'Status';
			}
		$this->headers[$field] = $value;
		return $this;
		}
}
