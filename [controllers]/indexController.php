<?php
class IndexController extends MainController{
	
	function routifyAction()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);

		$action = 'index';
		if($_SERVER['REQUEST_URI'] == '/' && $_CONFIG['ZAGLUSHKA_INDEX'])
			$action = 'zaglushka';

        $section = $CORE->params[0];
//        vd($CORE->params);
//        vd($section);
        if($section)
            $action = $section;

		$CORE->action = $action;
	}
	
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::FRONTEND);
		
		//$CONTENT->setTitle('ПаНКи');
		$CONTENT->title = ''.DOMAIN_CAPITAL.' :: Главная';
		//vd($CONTENT);
		
		$MODEL['dealType'] = DealType::code($_REQUEST['type']);
		$MODEL['cats'] = AdvCat::getFullCatsTree($status=Status::code(Status::ACTIVE));
		
		$MODEL['dealTypes'] = DealType::$items;
		
		$advsQuanList = AdvQuan::getListByCity($_GLOBALS['city']->id);
		//vd($advsQuanList);
		
		foreach($MODEL['cats'] as $cat)
		{
			$cat->advsCount = $advsQuanList[$cat->id]->quan;
			foreach($cat->subCats as $subCat)
				$subCat->advsCount = $advsQuanList[$subCat->id]->quan;
		}
		
		$MODEL['totalCount'] = AdvItem::getCount($params=array('statuses' => array(Status::code(Status::ACTIVE))));
		$MODEL['totalCountCurrentCity'] = AdvItem::getCount($params=array('statuses' => array(Status::code(Status::ACTIVE)), 'cityId'=>$_GLOBALS['city']->id, ));
		
		$MODEL['lastAdvs'] = AdvItem::getList($params=array('cityId'=>$_GLOBALS['city']->id, 'statuses'=>array(Status::code(Status::ACTIVE)), 'orderBy'=>'id DESC', 'limit'=>' LIMIT 0, 4 '));
		//vd($MODEL['lastAdvs']);
		
		# 	инициализируем медиа
		foreach($MODEL['lastAdvs'] as $item)
			$ids[] = $item->id;
		$medias = AdvMedia::getByPidsList($ids);
		foreach($MODEL['lastAdvs'] as $key=>$item)
			$MODEL['lastAdvs'][$key]->media = $medias[$item->id];
		

		Core::renderView('index/index.php', $MODEL);
	}
	
	
	
	function zaglushka()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		//Startup::execute(Startup::FRONTEND);
		
		$CORE->setLayout('zaglushka.php');
		
	}




    function geoPick_countriesJson()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::FRONTEND);
        $CORE->setLayout(null);

        $list = Country::getList(['status'=>Status::code(Status::ACTIVE)]);
        foreach ($list as $item)
            $arr[] = $item->json();

        echo json_encode($arr);
    }


    function geoPick_regionsJson()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::FRONTEND);
        $CORE->setLayout(null);

        $list = Region::getList(['countryId'=>$_REQUEST['countryId'], 'status'=>Status::code(Status::ACTIVE), 'orderBy'=>'name', ]);
        foreach ($list as $item)
            $arr[] = $item->json();

        echo json_encode($arr);
    }


    function geoPick_citiesJson()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::FRONTEND);
        $CORE->setLayout(null);

        $list = City::getList2(['regionId'=>$_REQUEST['regionId'], 'status'=>Status::code(Status::ACTIVE), 'orderBy'=>'name',]);
        foreach ($list as $item)
            $arr[] = $item->json();

        echo json_encode($arr);
    }




	
}

?>