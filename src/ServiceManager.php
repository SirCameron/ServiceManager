<?php
namespace SirCameron;

class ServiceManager{
	
	private $_config;
	private $_services = [];

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



	public function getConfig(){

		$config =& $this->_config;

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



	public function get( $serviceName ){

		$arguments = func_get_args();
		$hash = md5(serialize($arguments));
		
		if ( isset($this->_services[$hash]) ){
			return $this->_services[$hash];
		}

		$factory = $this->getConfig('ServiceManager','factories',$serviceName);
		if (is_callable($factory) ){
			$arguments[0] = $this;
			return $this->_services[$hash] = call_user_func_array($factory, $arguments);
		}

		throw new \RuntimeException( sprintf('No service found: %s',$serviceName) );
	}

}