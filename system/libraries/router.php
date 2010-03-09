<?php
class router {
	public function run_path($path)
		{
		$path = '/'.$path;
		$routes = s('config','routes.conf');
		foreach ($routes as $regex => $handler)
			{
			echo $regex.': '.$handler."<br/>\n";
			$regex = '@^'.$regex.'$@';
			$handler = explode(' ',$handler);
			$args = array();
			if (preg_match($regex,URL,&$args))
				{
				array_pop($args);
				call_user_func_array(array(s($handler[0]), $handler[1]),$args);
				break;
				}
			}
		}
}
