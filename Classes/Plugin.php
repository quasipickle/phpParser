<?php
namespace Phile\Plugin\Quasipickle\PhpParser;

class Plugin extends \Phile\Plugin\AbstractPlugin implements \Phile\Gateway\EventObserverInterface {

	private $filename;

	public function __construct() {
		 \Phile\Event::registerEvent('before_parse_content', $this);
	}

	public function on($eventKey, $data = null) {
		if($eventKey == 'before_parse_content'){
			$this->parse($data);
			return $data;
		}
	}

	private function parse(&$data){
		$content = $data['content'];
		$pattern = ':\[php\](.*?)\[/php\]:s';

		if(preg_match_all($pattern,$content,$matches,PREG_SET_ORDER)){
			$rendered = '';
			foreach($matches as $match){
				$full_string = $match[0];
				$params = json_decode($match[1]);

				ob_start();
				$this->run($params);
				$rendered = ob_get_clean();

				$content = str_replace($full_string, $rendered, $content);
			}
		}


		$data['page']->setContent($content);

	}

	private function run($params){
		if($params === NULL){
			echo 'Parameters for PHP file execution were not valid. Likely cause is an invalid JSON format.';
			return;
		}
		$this->filename = $params->file;//move $file into object space to prevent overwriting
		if(isset($params->vars)){
			foreach($params->vars as $name=>$value){
				${$name} = $value;
			}
		};

		include $this->filename;
	}
}