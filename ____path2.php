<?php function vd($a){echo '<pre>'; var_dump($a); echo '</pre>'; }

error_reporting(E_ERROR  | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING | E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE);
session_start();
//$_SESSION['timers'] = null;
//vd($_SESSION); return;
require_once('config.php');
require_once('header.php');

# 	глобальный таймер
$globalTimer = new Timer('ГЛОБАЛЬНЫЙ ТАЙМЕР', Timer::TYPE_GLOBAL_TIMER);

$url = $_SERVER['PATH_INFO'];

$urlParts = Url::parse($url);

//vd($urlParts);

if($_CONFIG['LANGS'][$urlParts[0]])		#	1-й кусок урла - язык
{
	define('IS_ADMINKA', false);
	define('LANG', $urlParts[0]);
	$_SESSION['lang'] = LANG;	#	для того, чтобы в аджаксовыхзапросах помнить язык
	$controllerIndexInUrl = 1;
}	
elseif($urlParts[0] == ADMIN_URL_SIGN)	#	админка
{
	define('IS_ADMINKA', true);
	define('LANG', $_CONFIG['DEFAULT_LANG']);
	$controllerIndexInUrl = 1;
	$_GLOBALS['TITLE'] = 'SLoNNe CMS';
} 	
else	#	Безъязыковый запрос (скорее всего AJAX)
{
	define('IS_ADMINKA', false);	
	define('LANG', $_SESSION['lang']);
	$controllerIndexInUrl = 0;
}
//vd($controllerIndexInUrl);
//vd($urlParts);
$controller = $urlParts[$controllerIndexInUrl];

if(!$controller) 
	$controller='index';
$controller .= 'Controller';


#	наполняем $_PARAMS
$urlSectionsCount=count($urlParts);
for ($i = ($controllerIndexInUrl+1); $i < $urlSectionsCount; $i++) 
	$_PARAMS[$i-($controllerIndexInUrl+1)] = $urlParts[$i];





if(Admin::isAdmin())
	$_GLOBALS['ADMIN'] = Admin::get($_SESSION['admin']['id'], $active=true);

#	наполнение глобальных переменных фронтэнда (ЕСЛИ ЕСТЬ ЛЭЙАУТ)
if(!IS_ADMINKA)
{
	/*if(!$_GLOBALS['NO_LAYOUT'])
		require_once('startup.php');*/
}
else	#	в любом случае для админки
{
	require_once(ADMIN_DIR.'/startup.php');
	
	#	редирект к авторизации
	if($controller!='authController' && !$_GLOBALS['ADMIN'] )
		Slonne::redirect("auth");	
		
	if($_GLOBALS['ADMIN'])
	{
		$_GLOBALS['ADMIN']->initGroup();
		$_GLOBALS['ADMIN']->group->initPrivilegesArr();
		//$ADMIN = $_GLOBALS['ADMIN'];
	}
}
	

ob_start();
$arr = NULL;



$controllerPath = (IS_ADMINKA ? ADMIN_DIR.'/' : '').CONTROLLERS_DIR.'/'.$controller.'.php'; 

if(!file_exists($controllerPath))
	$controller = 'errorController';

define('CURRENT_CONTROLLER', $controller);	
$controllerPath = (IS_ADMINKA && $controller != 'errorController' ? ADMIN_DIR.'/' : '').CONTROLLERS_DIR.'/'.CURRENT_CONTROLLER.'.php';

$t = new Timer('Подгрузка контроллера');
require_once($controllerPath);
//Slonne::loadDir( (IS_ADMINKA ? ADMIN_DIR.'/' : '') .  CONTROLLERS_DIR);
$t->stop();


#	задача контроллеров - наполнить переменные $ACTION и $CONTROLLER(при желании)
#	дальще просто вызывается соответствующий экшн соотв. контроллера:
MainController::action($ACTION, $CONTROLLER ? $CONTROLLER : CURRENT_CONTROLLER);	
$_GLOBALS['CONTENT']=ob_get_clean();


if(!$_GLOBALS['NO_LAYOUT'])
	$LAYOUT = $_GLOBALS['LAYOUT'] ? $_GLOBALS['LAYOUT'] : (IS_ADMINKA ? $_CONFIG['DEFAULT_ADMIN_LAYOUT'] : $_CONFIG['DEFAULT_LAYOUT']);

# 	запоминаем таймеры
if(!$_GLOBALS['NO_LAYOUT'])
{
	$globalTimer->stop(); 	# 	завершение глобального таймера
	unset($_SESSION['timers']);
}
$_SESSION['timers'] = array_merge($_SESSION['timers']?$_SESSION['timers']:array(), $_SESSION['timersNewPortion']);
unset($_SESSION['timersNewPortion']);

Timer::fixSessionTimers();


	
Slonne::layoutRender($LAYOUT);


?>