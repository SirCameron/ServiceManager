<?php
namespace SirCameron;

class ServiceManager{

	private static $config;
	private $_config;
	private static $services = [];

	public function __construct( $config ){

		if ( !is_array($config) ){
			throw new \RuntimeException('Incorrect config');
		}
		$this->_config = $config;

		if (is_array($addConfigs=$this->getConfig('ServiceManager','additionalConfigs'))){
			foreach ($addConfigs as $addConfig){
				if (file_exists($addConfig)){
					$addConfig = include($addConfig);
					$this->_config = array_merge($this->_config,$addConfig);
				}
			}
		}
	}


	public static function setConfig($config){
		self::$config = $config;
	}


	public static function getConfig(){

		$config =& self::$config;

		$keys = func_get_args();
		foreach ($keys as $key){
			if (array_key_exists($key, $config)){
				$config =& $config[$key];
			}
			else
			{
				return null;
			}
		}

		return $config;
	}



	public static function get( $serviceName ){

		$arguments = func_get_args();
		$hash = md5(serialize($arguments));

		if ( isset(self::$services[$hash]) &&  self::$services[$hash] !== null ){
			return self::$services[$hash];
		}

		$factory = self::config('ServiceManager','factories',$serviceName);
		if (is_callable($factory) ){
			$arguments = array_slice($arguments, 1);
			return self::$services[$hash] = call_user_func_array($factory, $arguments);
		}

		throw new \RuntimeException( sprintf('No service found: %s',$serviceName) );
	}

}
