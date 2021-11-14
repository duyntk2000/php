<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");

set_include_path("./src/model");
require_once("Voiture.php");
require_once("Image.php");
require_once("Account.php");
require_once("Comment.php");
require_once("AuthenticationManager.php");

set_include_path("./src/model/storage");
require_once("ImageStorage.php");
require_once("ImageStorageStub.php");
require_once("ImageStorageMySQL.php");

require_once("VoitureStorage.php");
require_once("VoitureStorageStub.php");
require_once("VoitureStorageFile.php");
require_once("VoitureStorageMySQL.php");

require_once("AccountStorage.php");
require_once("AccountStorageStub.php");
require_once("AccountStorageMySQL.php");

require_once("CommentStorage.php");
require_once("CommentStorageMySQL.php");

set_include_path("./src/model/builder");
require_once("VoitureBuilder.php");
require_once("CommentBuilder.php");
require_once("AccountBuilder.php");

set_include_path("./src/lib");
require_once("ObjectFileDB.php");

set_include_path("./src/control");
require_once("Controller.php");

set_include_path("./src/view");
require_once("View.php");
require_once("PrivateView.php");

require_once('/users/21811412/private/mysql_config.php');
/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 */
$router = new Router();
$dsn = "mysql:host=".MYSQL_HOST.";port=".MYSQL_PORT.";dbname=".MYSQL_DB.";charset=utf8";
$bd = new PDO($dsn, MYSQL_USER, MYSQL_PASSWORD);
$db = new VoitureStorageMySQL($bd);
$images = new ImageStorageMySQL($bd);
$accounts = new AccountStorageMySQL($bd);
$comments = new CommentStorageMySQL($bd);
$router->main($db, $images, $accounts, $comments);
?>
