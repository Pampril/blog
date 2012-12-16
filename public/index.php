<?php
defined('APPLICATION_PATH') || define('APPLICATION_PATH',
		realpath(dirname(__FILE__) . '/../application'));

defined('LIBRARY_PATH') || define('LIBRARY_PATH',
		realpath(dirname(__FILE__) . '/../library'));

defined('APPLICATION_ENV') || define('APPLICATION_ENV',
		(getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));


//on modifie l'include path de php
set_include_path(implode(PATH_SEPARATOR, array(realpath(LIBRARY_PATH), get_include_path())));

// on a besoin de zend app pour lancer l'application
require_once 'Zend/Application.php';

require_once "Zend/Loader.php";
Zend_Loader::registerAutoload();

//on lance la session
require_once 'Zend/Session.php';
Zend_Session::start();


//on créee l'application, on lance le bootstrap et l'application
$application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');


//on modifie l'include path de php
set_include_path(implode(PATH_SEPARATOR, array(realpath(APPLICATION_PATH), get_include_path())));

//permet l'upload d'images
defined('PUBLIC_PATH')|| define('PUBLIC_PATH', realpath(dirname(__FILE__)));

//require_once 'models/class_articles.php';
//require_once 'models/class_commentaire.php';
//require_once 'models/class_images.php';
//require_once 'models/class_user.php';

$application->bootstrap()->run();
?>