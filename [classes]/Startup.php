<?php
class Startup{
	
	const FRONTEND 	= 'frontend';
	const ADMIN  	= 'admin';
	
	
	function execute($type)
	{
		self::common();
		
		if($type)
		{
			if(method_exists('Startup', $type))
				call_user_func('Startup::'.$type);
			else echo 'eRRoR: uNDeFiNeD STaRTuP SCeNaRio <b>"'.$type.'"</b>.';
		}
	}
	
	
	
	
	
	########################
	#####  ФРОНТЭНД	########
	########################
	function frontend()
	{
		//global $CORE, $_GLOBALS, $_CONFIG, $_PARAMS, $_CONST;
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		
		$CONTENT->titlePostfix = $_CONFIG['SETTINGS']['title_postfix'];
		$CONTENT->titleSeparator = $_CONFIG['SETTINGS']['title_separator'];
		$CONTENT->metaKeywords = $_CONFIG['SETTINGS']['keywords'];
		$CONTENT->metaDescription = $_CONFIG['SETTINGS']['description'];
		
		$t = new Timer('Главное меню');


//        # 	города
//        $t = new Timer('города (запрос)');
//        $cities = City::getList(Country::KAZAKHSTAN_ID, Status::code(Status::ACTIVE), $orderBy='isLarge DESC, name');
//		$specialCities = [];
//		$otherCities = [];
//		foreach ($cities as $key=>$c)
//			if(in_array($c->name, ['Алматы', 'Астана', ]))
//			{
//				$c->isSpecial = true;
//				$specialCities[$key] = $c;
//			}
//			else
//				$otherCities[$key] = $c;
//		$cities = [];
//		foreach ($specialCities as $key=>$c)
//			$cities[$key] = $c;
//		foreach ($otherCities as $key=>$c)
//			$cities[$key] = $c;
//		//vd($cities);
//		$_GLOBALS['cities'] = $cities;
//		$t->stop();
	
		/*
		 $t = new Timer('запись городов в кэш');
		 file_put_contents(INFO_CACHE_DIR_PATH.'/'.INFO_CACHE_CITIES_FILE, json_encode($_GLOBALS['cities']));
		 $t->stop();
	
	
		 $t = new Timer('города (из кеша)');
		 $_GLOBALS['cities'] = City::getFromCacheFile();
		 $t->stop();
		 */



		#   получим все города и регионы
        $_GLOBALS['cities'] = GeoHelper::cities();
        $_GLOBALS['regions'] = GeoHelper::regions();
        $_GLOBALS['countries'] = GeoHelper::countries();
//        $_GLOBALS['cities'] = City::getList2(['status'=>Status::code(Status::ACTIVE)]);
//        $_GLOBALS['regions'] = Region::getList(['status'=>Status::code(Status::ACTIVE)]);
//        $_GLOBALS['countries'] = Country::getList(['status'=>Status::code(Status::ACTIVE)]);



		# 	выбранный город
		if($_REQUEST['globalCityId'])
		{
			if(GeoHelper::city($_REQUEST['globalCityId']))
				$_SESSION['cityId'] = GeoHelper::city($_REQUEST['globalCityId'])->id;
		}
		if(!$_SESSION['cityId'])
			$_SESSION['cityId'] = City::ALMATY_ID;

		$_SESSION['city'] = GeoHelper::city($_SESSION['cityId']);
		$GLOBALS['city'] = $_SESSION['city'];
        $_SESSION['region'] = GeoHelper::region($_SESSION['city']->regionId);
        $_SESSION['country'] = GeoHelper::country($_SESSION['region']->countryId) ;


		//vd($_GLOBALS['city']);




		#	инициализируем юзера
		if($_SESSION['user'])
		{
			$USER = User::get($_SESSION['user']['id']);
			if(!$USER)
				unset($_SESSION['user']);
			else
				$USER->city = $USER->cityId ? $_GLOBALS['cities'][$USER->cityId] : null;
		}


		
	}
	
	
	
	
	########################
	#####  АДМИН	########
	########################
	function admin()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);

		$CONTENT->title = 'SLoNNe CMS';
		
		#	редирект к авторизации
		if($CORE->controller!='authController' && !$ADMIN )
			Slonne::redirect("auth");
		
	}
	
	
	
	
	

	
	
	########################
	#####  ОБЩИЕ	########
	########################
	function common()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		
		#	инициализация настроек
		$_CONFIG['SETTINGS'] = Settings::get();
		
		//echo "!";
		# 	инициализация админа
		if($_SESSION['admin'])
		{
			$ADMIN = Admin::get($_SESSION['admin']['id'], Status::code(Status::ACTIVE));
			if($ADMIN)
				$ADMIN->initGroup();
		}

        fixFILESArray();
	}
	
	
	
	
	
}