<?php
	
	//ini_set('display_errors','On');
	//error_reporting(E_ALL);

	//error_reporting(E_ALL); ini_set('display_errors', '1');
	define('ROOT', dirname(dirname(__FILE__)));
	require_once ROOT . '/lib/bootstrap.php';
	require_once ROOT . '/lib/sag/src/Sag.php';
	require_once ROOT . '/lib/configuration.php';

	function __autoload($classname){
		include_once(ROOT."/classes/" . strtolower($classname) . ".php");
	}

	/* four method for HTTP */
	function get($route,$callback){	
		Bones::register($route,$callback,'GET');

	}

	function post($route,$callback){
		Bones::register($route,$callback,'POST');
	}

	function put($route,$callback){
		Bones::register($route,$callback,'PUT');
	}

	function delete($route,$callback){
		Bones::register($route,$callback,'DELETE');
	}

	function resolve(){
		Bones::resolve();
	}



	class Bones{
		private static $instance;
		public static $route_found = false;
		public $route = '';
		public $content = '';
		public $vars = array();
		public $route_segments = array();
		public $route_variable = array();
		public $couch;
		public $config;
		public $method = '';
		public static function get_instance(){
			if(!isset(self::$instance)){
				self::$instance = new Bones();
			}
			return self::$instance;
		}

		public function __construct(){
			$this->route = $this->get_route();
			$this->route_segments = explode('/',trim($this->route,'/'));
			$this->method = $this->get_method();
			$this->config = new Configuration();
			$this->couch = new Sag($this->config->db_server, $this->config->db_port);
			$this->couch->setDatabase($this->config->db_database);
		}

		public function form($key){
			return $_POST[$key];
		}

		public function make_route($path = ''){
			$url = explode('/',$_SERVER['PHP_SELF']);
			if($url[1] == "index.php"){
				return $path;
			}else{
				return '/' . $url[1] . $path;
			}
		}

		public function request($key){
			return $this->route_variable[$key];
		}

		public function display_alert($variable = 'error'){
			if(isset($this->vars[$variable])){
				return "<div class='alert alert-" .$variable . "' style='margin-top:60px;'><a class='close' data-dismiss='alert'>x</a>".$this->vars[$variable]."</div>";
			}
		}

		public function redirect($path='/'){
			header('Location:' . $this->make_route($path));
		}

		protected function get_route(){
			parse_str($_SERVER['QUERY_STRING'],$route);
			if($route)
			{
				return $route['request'];
			}else{
				return '/';

			}
			

		}

		protected function get_method(){
			return isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
		}

		public function set($index,$value){

			$this->vars[$index] = $value;
			//var_dump($this->vars);
		}

		public function render($view,$layout="layout"){

			$this->content = ROOT . '/views/' . $view . '.php' ;
			//echo 'hello';
			
			foreach($this->vars as $key => $value){
				$$key = $value;
				//var_dump($$key);
			}
			if(!$layout){
				include($this->content);
			}else{
				//echo ROOT.'/views/'. $layout . '.php';	
				

				$result = require_once(ROOT .'/views/'.$layout.'.php');
				//var_dump($result);
			}
		}


		public static function register($route,$callback,$method){
			if(!static::$route_found){
				$bones = static::get_instance();
				$url_parts = explode('/',trim($route,'/'));
				$matched = null;

				if(count($bones->route_segments) == count($url_parts)){
					foreach ($url_parts as $key => $part) {
						if(strpos($part, ":") !== false){
							$bones->route_variable[substr($part,1)] = $bones->route_segments[$key];
						}else{
							if($part == $bones->route_segments[$key]){
								if(!$matched){
									$matched = true;
								}
							}else{
								$matched = false;
							}
						}
					}
				}else{
					$matched = false;
				}

				if(!$matched || $bones->method != $method){
					return false;
				}else{
					static::$route_found = true;
					echo $callback($bones);
				}
			}
		}

		public function error500($exception){
			$this->set('exception',$exception);
			$this->render('error/500');
			exit;
		}

		public function error404(){
			$this->render('error/404');
			exit;


		}

		public static function resolve(){
			if(!static::$route_found){
				$bones = static::get_instance();
				$bones->error404();
			}
		}
	}
?>