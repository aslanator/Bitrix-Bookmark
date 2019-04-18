<?php

namespace Bookmark;

class pageDownloader{


	/**
	 * @var resource
	 */
    protected $curl;
    
    /**
	 * @var mixed
	 */

    protected $content;
    
    /**
	 * @var int
	 */
    protected $lastErrorCode;
    
    /**
	 * @var string
	 */
    protected $lastErrorMessage;
    
    /**
	 * @var resource
	 */
    protected $header;
    
    /**
	 * @var array
	 */
	protected $options = Array();

	public function __construct(String $url, Array $options = []){
		$this->curl = \curl_init($url);
        $this->setOptions($options);
        $this->sendRequest();
	}

	/**
	 * @return void
	 */

	public function sendRequest(){
		$this->content = \curl_exec( $this->curl );
		$this->lastErrorCode     = \curl_errno( $this->curl );
		$this->lastErrorMessage  = \curl_error( $this->curl );
		$this->header  = \curl_getinfo( $this->curl );
		\curl_close( $this->curl );
	}

	/**
	 * @return Array|false
	 */

	public function getError(){
		if($this->lastErrorCode || $this->lastErrorMessage){
			return ['CODE' => $this->lastErrorCode, 'MESSAGE' => $this->lastErrorMessage];
		}
		return false;
	}

	/**
	* @return Array|false
	*/

	public function getHeader(){
		return $this->getProperty('header');
	}

	/**
	* @return Array|false
	*/

	public function getContent(){
		return $this->getProperty('content');
	}
	
	/**
	 * @param String $name Name of proprty to return
	 * @return mixed
	 */

	private function getProperty(String $name){
		if($this->$name)
			return $this->$name;
		return false;
	}

	/**
	 * Set curl options. Set some options basic value, if it do not exists in argument
	 * @return void
	 */

	public function setOptions(Array $options):void {
		$defaultOptions = [
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.86 Safari/537.36',
			CURLOPT_TIMEOUT => 120,
			CURLOPT_POST => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_CUSTOMREQUEST => 'GET'
		];

		foreach($options as $key => $option){
			$defaultOptions[$key] = $option;
		}

		\curl_setopt_array( $this->curl, $defaultOptions );
	}

}