<?php
class damien {
	public function define_constants()
		{
		/*
		The following constants will be set, and here are some typical values:
			DIR_BASE: /var/www/
			DIR_APPLICATION: /var/www/application/
			DIR_CACHE: /var/www/cache/
			DIR_CONFIG: /var/www/config/
			DIR_HELPERS: /var/www/helpers/
			DIR_LIBRARIES: /var/www/libraries/
			DIR_LOGS: /var/www/logs/
			DIR_PUBLIC: /var/www/public/
			DIR_COMPATIBILITY: /var/www/compatibility/
			DIR_CONTROLLERS: /var/www/application/controllers/
			DIR_MODELS: /var/www/application/models/
			DIR_VIEWS: /var/www/application/views/
			URL: class/method/param1/param2
			URL_BASE: ../../../
		*/
		
		//publicdir: the directory of the public folder, where the entry point is
		$publicdir = substr($_SERVER['SCRIPT_FILENAME'],0,strrpos($_SERVER['SCRIPT_FILENAME'],'/'));
		
		//DIR_BASE: the absolute directory of the base of the framework.
		//something like /var/www/
		define('DIR_BASE',substr($publicdir,0,strrpos($publicdir,'/')).'/');
		
		//folders: the folders to be put into constants
		//DIR_APPLICATION should be something like /var/www/application/, and so on
		$folders = array('application','cache','config','helpers','libraries','logs','public','compatibility',
				'application/controllers','application/models','application/views');
		foreach ($folders as $folder)
			{
			$parts = explode('/',$folder);
			$path = array();
			foreach ($parts as $part_id => $part)
				{
				$path[] = s('config')->dir[$part] ? s('config')->dir[$part] : $part; //loff
				}
			$constant_name = end($parts);
			$path = implode('/',$path);
			define('DIR_'.strtoupper($constant_name),DIR_BASE.$path.'/');
			}
		
		//URL: everything in REQUEST_URI minus the basedir as defined in the configuration
		define('URL',substr($_SERVER['REQUEST_URI'],strlen(s('config')->host_basedir)+1));
		
		//URL_BASE: the relative path to the base directory for use in views
		//If the URL is "cake", URL_BASE will be "./". If the URL is "cake/14", URL_BASE will be ../
		//Mainly for use in views, eg "<img src="<?php echo URL_BASE.'myimg.png'"/>
		//variable substr_count used so that it doesn't have to be run twice
		$substr_count = substr_count(URL,'/');
		define('URL_BASE',($substr_count) ? str_repeat('../',$substr_count) : './');
		
		//EXT should be .php
		define('EXT',substr(__FILE__,strrpos(__FILE__,'.')));
		}
}
