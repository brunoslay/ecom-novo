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
			header("Location: /git/ecommerce/admin/login");
			exit;
		}
	}

	public static function logout(){
		
		$_SESSION[User::SESSION] = NULL;

	}

	public static function listAll(){
		
		$sql = new Sql();

		return $sql->select("SELECT * FROM  tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");

	}

	// procedure
	public function save(){
		
		$sql = new Sql();

		$results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", [
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
	 	]);

	 	$this->setData($results[0]);

	}

	public function get($iduser){
		
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM  tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser", [
			":iduser"=>$iduser
		]);

		$this->setData($results[0]);

	}

	public function update(){
		
		$sql = new Sql();

		$results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", [
			":iduser"=>$this->getiduser(),
			":desperson"=>$this->getdesperson(),
			":deslogin"=>$this->getdeslogin(),
			":despassword"=>$this->getdespassword(),
			":desemail"=>$this->getdesemail(),
			":nrphone"=>$this->getnrphone(),
			":inadmin"=>$this->getinadmin()
	 	]);

	 	$this->setData($results[0]);

	}

	public function delete(){
		
		$sql = new Sql();

		$sql->select("CALL sp_users_delete(:iduser)", [
			":iduser"=>$this->getiduser()
	 	]);

	}

}

 ?>