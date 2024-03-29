<?php 
session_start();
require_once("vendor/autoload.php");

use \Slim\Slim;
use \Hcode\Page;
use \Hcode\PageAdmin;
use Hcode\Model\User;

$app = new Slim();

$app->config('debug', true);

$app->get('/', function() {
    
	// $sql = new Hcode\DB\Sql();

	// $results = $sql->select("SELECT * FROM tb_users");

	// echo json_encode($results);

	$page = new Page();

	$page->setTpl("index");

});

$app->get('/admin/', function() {

	User::verifyLogin();

	$page = new PageAdmin();

	$page->setTpl("index");

});

$app->get("/admin/login/", function() {

	$page = new  PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("login");

});

$app->post("/admin/login/", function() {

	// echo $_POST["login"], $_POST["password"];

	User::login($_POST["login"], $_POST["password"]);

	header("Location: /git/ecommerce/admin");
	exit;

});

$app->get("/admin/logout/", function(){
	User::logout();

	header("Location: /git/ecommerce/admin/login");
	exit;
});

$app->get("/admin/users/", function(){

	User::verifyLogin();
	$users = User::listAll();

	$page = new  PageAdmin();

	$page->setTpl("users", [
		"users"=>$users
	]);

});

$app->get("/admin/users/create", function(){

	User::verifyLogin();

	$page = new  PageAdmin();

	$page->setTpl("users-create");

});
// deleta
$app->get("/admin/users/:iduser/delete", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$user->delete();

	header("Location: ../");
	exit;

});

// pega o parametro :iduser e joga pro param da função
$app->get("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$user->get((int)$iduser);

	$page = new  PageAdmin();

	$page->setTpl("users-update", [
		"user"=>$user->getValues()
	]);

});

$app->post("/admin/users/create", function(){

	User::verifyLogin();

	// var_dump($_POST); //esta recebendo o dados do form corretamente

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

 		"cost"=>12

 	]);


	$user->setData($_POST);

	$user->save();

	header("Location: ./");
	exit;

	// var_dump($user);

});

$app->post("/admin/users/:iduser", function($iduser){

	User::verifyLogin();

	$user = new User();

	$_POST["inadmin"] = (isset($_POST["inadmin"]))?1:0;

	$user->get((int)$iduser);

	$user->setData($_POST);

	$user->update();

	header("Location: ./");
	exit;

});


$app->get("/admin/forgot", function(){
	$page = new  PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot");
});

$app->post("/admin/forgot", function(){
	
	
	$user = User::getForgot($_POST["email"]);

	header("Location: /git/ecommerce/admin/forgot/sent");
	exit;

});

$app->get("/admin/forgot/sent", function(){
	$page = new  PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-sent");
});

$app->get("/admin/forgot/reset", function(){

	$user = User::ValidForgotDecrypt($_GET["code"]);

	$page = new  PageAdmin([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("forgot-reset", [
		"name"=>$user["desperson"],
		"code"=>$_GET["code"]
	]);
});

$app->post("/admin/forgot/reset", function(){

	$forgot = User::ValidForgotDecrypt($_POST["code"]);

	User::setForgotUsed($forgot["idrecovery"]);

	$user = new User();

	$user->get((int)$forgot["iduser"]);

	$password = password_hash($_POST["password"], PASSWORD_DEFAULT, [
		"cost"=>12
	]);

	$user->setPassword($password);


	$page = new  PageAdmin([
		"header"=>false,
		"footer"=>false
	]);


	//a pagina não precisa de nenhuma variavel
	$page->setTpl("forgot-reset-success");
});

$app->run();

 ?>