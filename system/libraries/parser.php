<?php
class parser extends has_drivers {
	public function parse($data,$filters,$ext = 'php')
		{
		foreach ($filters as $filter)
			{
			$data = $this->driver($filter)->$ext($data);
			}
		return $data;
		}
	public function parsefile($file)
		{
		$filters = explode('.',substr($file,strrpos($file,'/')+1));
		array_shift($filters);
		$ext = array_pop($filters);
		return (count($filters) == 0) ? false : $this->parse(file_get_contents($file),$filters,$ext);
		}
}
