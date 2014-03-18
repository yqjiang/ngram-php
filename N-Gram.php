<?php
class NGram {
	protected $windowSize = 3;
	protected $type = 'byte';
	protected $gramString = '';
	protected $limit = -1;
	protected $NGrams = array();
	/**
	 * The constructor
	 */
	public function __construct($windowSize = 3, $type = 'byte', $limit = -1) {
		$this->windowSize = $windowSize;
		$this->type =  $type ;
		$this->limit = $limit;
	}
	
	public function processText($text) {
		$this->gramString = $text;
		$this->blankToUnderScore();
		$this->process();
	}
	
	public function getNgram($windowSize = NULL, $orderby = 'ngram', $onlyfirst = 10000, $normalize= TRUE) {
		if(!$windowSize) {
			$windowSize = $this->windowSize;
		}
		
		$ng = $this->NGrams[$windowSize-1];
		
		switch($orderby) {
			case 'frequency':
			arsort($ng);
			break;
		}
		
		if($normalize) {
			// Calculate total
			$total = 0;
			foreach ($ng as $key => $value) {
				$total += $value;
			}
			
			foreach ($ng as $key => $value) {
				$ng[$key] = $ng[$key]/$total;
			}
		}
		
		if($onlyfirst < sizeof($ng)) {
			$ng = array_slice ($ng,0,$onlyfirst);
		}
		return $ng;
	}
	
	private function process() {
		switch($this->type) {
			case 'byte':
			$this->parseByte($this->getByteArray());
			break;
			case 'word':
			$this->parseByte($this->getWordArray());
			break;
			default:
			break;
		}
	}
	
	private function parseByte($keyArray)  {
		for($i=1;$i <= $this->windowSize;$i++) {
			$ng = $this->parseByNumber($keyArray,$i);
			$this->NGrams[] = $ng;
		}
	}
	
	private function getByteArray() {
		return preg_split ('//', $this->gramString, $this->limit, PREG_SPLIT_NO_EMPTY);
	}
	
	private function getWordArray() {
		return str_word_count($this->gramString,1);
	}
	
	private function parseByNumber($keyArray, $number = NULL)  {
		if(!$number) {	
			$number = $this -> windowSize;
		}
		$ng = array();
		$ngram_array = $keyArray;
		$length = sizeof($ngram_array) - $number + 1;
		for($i =0; $i<$length; $i++) {
			$tmp_token = '';
			for($j = 0; $j<$number;$j++) {
				$tmp_token .= $ngram_array[$i+$j].' ';
			}
			if(!in_array($tmp_token,array_keys($ng))) {
				$ng[$tmp_token] =  1;
			}
			else {
				$ng[$tmp_token]++;
			}
		}
		return $ng;
	}
	
	private function blankToUnderScore() {
		$this->gramString = str_replace(" ","_",$this->gramString);
	}
}
?>
