<?php  
namespace Hcode;

class Model {

	private $values = [];

	public function __call($name, $args){

		$method = substr($name, 0, 3);
		$fieldName = substr($name, 3, strlen($name));


		// var_dump($method, $fieldName);
		//exit;

		switch ($method) {
			case 'get':
				return $this->values[$fieldName]; //se conter algo, verdadeiro
				break;

			case 'set':
				return $this->values[$fieldName] = $args[0];
				break;
			default:
				# code...
				break;
		}

		
	}

	public function setData($data = array()){

		foreach ($data as $key => $value) {

			// as chaves permite o php interpretar e juntar a string mais a variavel, torando-se outra var ou metodo neste caso.
			$this->{"set".$key}($value);
		}

	}

	public function getValues(){
		return $this->values;

		// pq não acessamos o atributo diretamente? ele é privado e assim é mais seguro
	}
}


?>