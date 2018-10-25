<?php 
namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class User extends Model {

	const SESSION = "User";

	public static function login ($login, $password){

		$sql =  new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
			":LOGIN"=>$login
		));

		if (count($results) === 0) {
			throw new \Exception("Usuário inexistente ou senha inválida.");
			
		}

		$data = $results[0];

		// essa funcao retorna ture ou false
		if (password_verify($password, $data["despassword"]) === true) {
			$user = new User();

			
			$user->setData($data); // pegamos o objeto inteiro
			//$user->setiduser($data["iduser"]);

			$_SESSION[User::SESSION] = $user->getValues();

			return $user;

			//var_dump($user);
			//exit();



		} else {
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}

	}

	public static function verifyLogin($inadmin = true){

		if (
			!isset($_SESSION[User::SESSION]) // ta vazio?
			||
			!$_SESSION[User::SESSION] // existe sessao?
			||
			!(int)$_SESSION[User::SESSION]["iduser"] > 0 // é maior que zero?
			||
			(bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin // é admin?
		) {
			header("Location: /admin/login");
			exit;
		}
	}

	public static function logout(){
		
		$_SESSION[User::SESSION] = NULL;

	}

}

 ?>