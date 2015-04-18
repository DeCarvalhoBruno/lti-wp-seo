<?php namespace Lti\Seo\Test\Datatype;

class JSON {

	private $content;
	/**
	 * @var DOMDocument
	 */
	private $dom;

	private $baseXPath = "/html/head/";

	public function __construct($content){
		$this->content = $content;
		$this->dom = $this->makeDOM($content);
	}

	public function makeDOM($content){
		$dom = new DOMDocument();
		@$dom->loadHTML($content);
		return $dom;
	}

	public function has($query){
		$xpath = new DOMXPath($this->dom);
		$tags = $xpath->query($query)->length;

		if($tags>0){
			return true;
		}
		return false;
	}

	public function hasTagWithContent($node,$attribute,$attributeName,$content, $contentName){
		$xpath = new DOMXPath($this->dom);
		$tags = $xpath->query(sprintf('%s%s[@%s="%s"][@%s="%s"]',$this->baseXPath,$node,$attribute,$attributeName,$content, $contentName))->length;

		if($tags>0){
			return true;
		}
		return false;
	}

	public function hasTag($node,$attribute,$attributeName,$content){
		$xpath = new DOMXPath($this->dom);
		$tags = $xpath->query(sprintf('%s%s[@%s="%s"][@%s]',$this->baseXPath,$node,$attribute,$attributeName,$content))->length;

		if($tags>0){
			return true;
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function get() {
		echo "\n\n";
		print_r($this->content);
		echo "\n\n";
	}

	/**
	 * @param string $content
	 */
	public function set( $content ) {
		$this->content = $content;
	}

	public function count(){
		$xpath = new DOMXPath($this->dom);
		return $xpath->query('/html/head/*')->length;
	}


}