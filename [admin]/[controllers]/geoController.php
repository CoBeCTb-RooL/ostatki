<?php
class GeoController extends MainController{
	
	function routifyAction()
	{
		global $CORE;
		$section = $CORE->params[0];
		$p = $CORE->params[1];
	
		if($section == 'list')
			$action='list1';
	
		if($action)
			$CORE->action = $action;
	}
	
	
	function index()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);



        if(isset($_REQUEST['from_file']))
        {

            #   СОБИРАЕМ ДАННЫЕ ИЗ ФАЙЛА
//            $file = ROOT.'/'.INCLUDE_DIR.'/content/regions_kz.txt';
            $file = ROOT.'/'.INCLUDE_DIR.'/content/regions_common.txt';
            $str = file_get_contents($file);
            $tmp = explode("\n", $str);

            $currentCountry = '';
            $currentRegion = '';
            $currentCity = '';
            foreach($tmp as $key=>$val)
            {
                $tmp2 = explode("\t", $val);
                $str.= '<hr>';
//                vd($val);
//                vd($tmp2);

                if(trim($tmp2[0]))
                {
                    $currentCountry = trim($tmp2[0]);
                    $str.= 'ЭТО СТРАНА!!';
                    continue;
                }


                if(trim($tmp2[1]))
                {
                    $currentRegion = trim($tmp2[1]);
                    $str.=' ЭТО РЕГИОН!!';
                    continue;
                }


                if(trim($tmp2[2]))
                {
                    $currentCity = trim($tmp2[2]);

                    #   если какие-то партаки на входе
                    if($currentCity == 'Нур- султан')
                        $currentCity = 'Нур-Султан';

                    $arr[$currentCountry][$currentRegion][] = $currentCity;
                }


                $str.= '
                <div style="border: 1px solid #eaeaea; ">
                    country: '.$currentCountry.'<br>
                    region: '.$currentRegion.'<br>
                    city: '.$currentCity.'<br>

                </div> ';
            }


            #   достаём существующие
            $existingCountries = Country::getList();
            $existingRegions = Region::getList();
            $existingCities = City::getList();
//            vd($existingRegions);

            vd($arr);
            echo '<a href="/admin/geo/?from_file&go=1">Сохранить</a><br><br><br><br>';


            if($_REQUEST['go'])
            {
                echo '<hr><hr><hr>';
                #   начинаем создавать

                $currentCountry = $currentRegion = $currentCity = null;
                foreach ($arr as $countryName=>$regions)
                {
                    if($countryName == 'Россия')
                        $countryName = 'Роися';

                    $country = null;
                    foreach ($existingCountries as $c)
                        if($c->name == $countryName)
                            $country = $c;


                    #   создаём страну, если надо
                    if(!$country)
                    {
                        $country = new Country();
                        $country->status = Status::code(Status::ACTIVE);
                        $country->name = $countryName;

                        $country->insert();

                        $existingCountries[] = $country;
                    }



                    #   работаем с регионами
                    foreach ($regions as $regionName=>$cities)
                    {
                        $region = null;

                        foreach ($existingRegions as $r)
                            if($r->name == $regionName && ($r->countryId == $country->id || !$r->countryId)  )
                                $region = $r;

                        #   создаём или обновляем регион
                        if(!$region)
                        {
                            $region = new Region();
                            $region->status = Status::code(Status::ACTIVE);
                            $region->name = $regionName;
                        }

                        $region->countryId = $country->id;

                        if($region->id)
                            $region->update();
                        else
                        {
                            $region->insert();

                            $existingRegions[] = $region;
                        }


                        #   работаем с городами
                        foreach ($cities as $cityName)
                        {
                            $city = null;
                            foreach ($existingCities as $c)
                                if($c->name == $cityName && ($c->countryId == $country->id || !$c->countryId) && ($c->regionId == $region->id || !$c->regionId)    )
                                    $city = $c;

                            #   создаём или апдейтим
                            if(!$city)
                            {
                                $city = new City();
                                $city->name = $cityName;
                                $city->status = Status::code(Status::ACTIVE);
                            }
                            $city->countryId = $country->id;
                            $city->regionId = $region->id;

                            if($city->id)
                                $city->update();
                            else
                            {
                                $city->insert();
                                $existingCities[] = $city;
                            }
                        }
                    }
                }
                echo '<br><br><br><br>';
            }
        }


		
		
		Core::renderView('geo/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);

		$MODEL['countriesDict'] = Country::getList();
		$MODEL['regionsDict'] = Region::getList();
		$MODEL['citiesDict'] = City::getList();


		Core::renderView('geo/list.php', $MODEL);
	}



	function edit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);

		$error = '';
		if($id = intval($_REQUEST['id']))
		{
			$MODEL['item'] = City::get($id);
			if(isset($_REQUEST['id']) && !$MODEL['item'])
				$error = 'Ошибка! Мера не найдена. ';
		}

		$MODEL['error'] = $error;

		Core::renderView('geo/edit.php', $MODEL);
	}
	
	
	
	function editSubmit()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
	//vd($_REQUEST);
		$errors = null;
		if($name=trim($_REQUEST['name']))
		{
			$item = City::get($_REQUEST['id']);
			
			if($item)
			{
				$prev = clone $item;
				/*$prev = new City();
				$prev->name = $item->name;
				$prev->isLarge = $item->isLarge;*/
				
				$byName = City::getByNameAndCountryId($name, Country::KAZAKHSTAN_ID);
				if($byName && $byName->id != $item->id)
					$errors[] = Slonne::setError('name', 'Этот город уже существует в этой стране!');
				else
				{	
					$item->name = $name;
					$item->isLarge = $_REQUEST['isLarge'] ? true : false; 
					
					$changes = array();
					if($item->name != $prev->name)
						$changes[] = 'Название изменено с "'.$prev->name.'" на "'.$item->name.'"';
					if($item->isLarge != $prev->isLarge)
						$changes[] = 'Поле КРУПНЫЙ изменено с "'.($prev->isLarge?'ДА':'НЕТ').'" на "'.($item->isLarge?'ДА':'НЕТ').'"';
	
					if(count($changes))
					{
						$item->update();
						$journalEntryType = JournalEntryType::code(JournalEntryType::UPDATE);
						$msg = join('\n   - ', $changes);
						$param1 = $item->name;
					}
					else
						$noNeedToJournalize = true;
				}
			}
			else
			{
				$byName = City::getByNameAndCountryId($name, Country::KAZAKHSTAN_ID);
				if($byName)
					$errors[] = Slonne::setError('name', 'Этот город уже существует в этой стране!');
				else
				{
					$item = new City();
					$item->name = $name;
					$item->status = Status::code(Status::ACTIVE);
					$item->countryId = Country::KAZAKHSTAN_ID;
					$item->isLarge = $_REQUEST['isLarge'] ? true : false;
					$item->id = $item->insert();
					$msg = 'Создан';
					$journalEntryType = JournalEntryType::code(JournalEntryType::CREATE);
				}
			}
			
			if(!$noNeedToJournalize)
			{
				$je = new JournalEntry();
				$je->objectType = Object::code(Object::CITY);
				$je->objectId = $item->id;
				$je->journalEntryType = $journalEntryType;
				$je->comment = $msg;
				$je->adminId = $ADMIN->id;
				$je->param1 = $param1;
				$je->insert();
			}
		}
		else 
			$errors[] = Slonne::setError('name', 'Вы не указали значение!');
		
	
		$json['errors'] = $errors;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	
	
	function switchCountryStatus()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		$CORE->setLayout(null);
	
		$error = '';
	
		$item = Country::get($_REQUEST['id']);
		if($item)
		{
			$prevStatus = $item->status;
			$statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
			//vd($statusToBe);
	
			$item->status = $statusToBe;
			$item->update();
			
			$je = new JournalEntry();
			$je->objectType = Object::code(Object::CITY);
			$je->objectId = $item->id;
			$je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
			$je->comment = 'Смена статуса с "'.$prevStatus->code.'" на "'.$statusToBe->code.'"';
			$je->adminId = $ADMIN->id;
			$je->param1 = $item->status->num;
			$je->insert();
		}
		else
			$error='Ошибка! Не найден город '.$_REQUEST['id'].'';
	
		$json['error'] = $error;
		$json['status'] = $statusToBe;

		echo json_encode($json);
	}
	
	
	



	public function indexNew()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);


        Core::renderView('geo/indexNew.php', $model);
    }


    function countriesList()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        $CORE->setLayout(null);

        $MODEL = [
            'list'=>Country::getList(),
            'statusClick' => 'Geo.Country.switchStatus(_ID_)',
            'click' => 'Geo.Country.click(_ID_)',
            'edit' => 'Geo.Country.edit(_ID_)',
        ];

//        Core::renderView('geo/countriesList.php', $MODEL);
        Core::renderView('geo/listPartial.php', $MODEL);
    }

    function countrySwitchStatus()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        $CORE->setLayout(null);

        $error = '';

        $item = Country::get($_REQUEST['id']);
        if($item)
        {
            $prevStatus = $item->status;
            $statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
            //vd($statusToBe);

            $item->status = $statusToBe;
            $item->update();

            $je = new JournalEntry();
            $je->objectType = Object::code(Object::COUNTRY);
            $je->objectId = $item->id;
            $je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
            $je->comment = 'Смена статуса с "'.$prevStatus->code.'" на "'.$statusToBe->code.'"';
            $je->adminId = $ADMIN->id;
            $je->param1 = $item->status->num;
            $je->insert();
        }
        else
            $error='Ошибка! Не найден город '.$_REQUEST['id'].'';

        $json['error'] = $error;
        $json['status'] = $statusToBe;

        echo json_encode($json);
    }



    public function countryEditForm()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);
        $CORE->setLayout(null);

        if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
        {
            if($id = intval($_REQUEST['id']))
            {
                $MODEL['item'] = Country::get($id);
                if(isset($_REQUEST['id']) && !$MODEL['item'])
                    $MODEL['error'] = 'Ошибка! Страна не найдена. ';
            }
        }
        else
            $MODEL['error'] = Error::NO_ACCESS_ERROR;


        Core::renderView('geo/countryEdit.php', $MODEL);
    }

    public function countryEditSubmit()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);
        $CORE->setLayout(null);

        if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
        {
//            vd($_REQUEST);
            $item = Country::get($_REQUEST['id']);

            $byName = Country::getList(['name'=>$_REQUEST['name'], ]);
            $byName = $byName[0];

            if($byName && ( ($item && $item->id!=$byName->id) || !$item->id)  )
                $errors[] = Slonne::setError('name', 'Такая страна уже существует!');
            else
            {
                if(!$item)
                {
                    $item = new Country();
                    $item->status = Status::code(Status::ACTIVE);
                }

                $item->setDataFromArray($_REQUEST);
                $validateResult = $item->validate();
                $errors = array_merge($errors ? $errors : [], $validateResult ? $validateResult : []);
//                vd($errors);

                #   сохраняем
                if(!$errors)
                {
                    if($item->id)
                        $item->update();
                    else
                        $item->insert();
                }
            }
        }
        else
            $errors[] = Slonne::setError(Error::NO_ACCESS_ERROR);


        $res = [
            'errors'=>$errors,
        ];
        vd($res);
        echo '<script>window.top.Geo.Country.editSubmitComplete('.json_encode($res).')</script>';
    }



    function regionsList()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        $CORE->setLayout(null);

//        $MODEL['list'] = Region::getList(['countryId'=>$_REQUEST['countryId']]);
        $MODEL = [
            'list'=>Region::getList(['countryId'=>$_REQUEST['countryId']]),
            'statusClick' => 'Geo.Region.switchStatus(_ID_)',
            'click' => 'Geo.Region.click(_ID_)',
            'edit' => 'Geo.Region.edit(_ID_)',
        ];


//        Core::renderView('geo/regionsList.php', $MODEL);
        Core::renderView('geo/listPartial.php', $MODEL);
    }


    function regionSwitchStatus()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        $CORE->setLayout(null);

        $error = '';

        $item = Region::get($_REQUEST['id']);
        if($item)
        {
            $prevStatus = $item->status;
            $statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
            //vd($statusToBe);

            $item->status = $statusToBe;
            $item->update();

            $je = new JournalEntry();
            $je->objectType = Object::code(Object::REGION);
            $je->objectId = $item->id;
            $je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
            $je->comment = 'Смена статуса с "'.$prevStatus->code.'" на "'.$statusToBe->code.'"';
            $je->adminId = $ADMIN->id;
            $je->param1 = $item->status->num;
            $je->insert();
        }
        else
            $error='Ошибка! Не найден город '.$_REQUEST['id'].'';

        $json['error'] = $error;
        $json['status'] = $statusToBe;

        echo json_encode($json);
    }


    public function regionEditForm()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);
        $CORE->setLayout(null);

        if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
        {
            if($id = intval($_REQUEST['id']))
            {
                $MODEL['item'] = Region::get($id);
                if(isset($_REQUEST['id']) && !$MODEL['item'])
                    $MODEL['error'] = 'Ошибка! Регион не найден. ';
            }
            $MODEL['countries'] = Country::getList();
            $MODEL['currentCountryId'] = $_REQUEST['currentCountryId'];
        }
        else
            $MODEL['error'] = Error::NO_ACCESS_ERROR;


        Core::renderView('geo/regionEdit.php', $MODEL);
    }


    public function regionEditSubmit()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);
        $CORE->setLayout(null);

        if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
        {
//            vd($_REQUEST);
            $item = Region::get($_REQUEST['id']);

            $byName = Region::getList(['name'=>$_REQUEST['name'], ]);
            $byName = $byName[0];

            if($byName && ( ($item && $item->id!=$byName->id) || !$item->id)  )
                $errors[] = Slonne::setError('name', 'Такой регион уже существует!');
            else
            {
                if(!$item)
                {
                    $item = new Region();
                    $item->status = Status::code(Status::ACTIVE);
                }

                $item->setDataFromArray($_REQUEST);
                $validateResult = $item->validate();
                $errors = array_merge($errors ? $errors : [], $validateResult ? $validateResult : []);
//                vd($errors);

                #   сохраняем
                if(!$errors)
                {
                    if($item->id)
                        $item->update();
                    else
                        $item->insert();
                }
            }
        }
        else
            $errors[] = Slonne::setError(Error::NO_ACCESS_ERROR);


        $res = [
            'errors'=>$errors,
        ];
        vd($res);
        echo '<script>window.top.Geo.Region.editSubmitComplete('.json_encode($res).')</script>';
    }




    function citiesList()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        $CORE->setLayout(null);

//        $MODEL['list'] = City::getList2(['regionId'=>$_REQUEST['regionId']]);
//        Core::renderView('geo/citiesList.php', $MODEL);
        $MODEL = [
            'list'=>City::getList2(['regionId'=>$_REQUEST['regionId']]),
            'statusClick' => 'Geo.City.switchStatus(_ID_)',
            'click' => 'Geo.City.click(_ID_)',
            'edit' => 'Geo.City.edit(_ID_)',
        ];

        Core::renderView('geo/listPartial.php', $MODEL);
    }

    function citySwitchStatus()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        $CORE->setLayout(null);

        $error = '';

        $item = City::get($_REQUEST['id']);
        if($item)
        {
            $prevStatus = $item->status;
            $statusToBe = $item->status->code == Status::ACTIVE ? Status::code(Status::INACTIVE) : Status::code(Status::ACTIVE);
            //vd($statusToBe);

            $item->status = $statusToBe;
            $item->update();

            $je = new JournalEntry();
            $je->objectType = Object::code(Object::REGION);
            $je->objectId = $item->id;
            $je->journalEntryType = JournalEntryType::code(JournalEntryType::STATUS_CHANGED);
            $je->comment = 'Смена статуса с "'.$prevStatus->code.'" на "'.$statusToBe->code.'"';
            $je->adminId = $ADMIN->id;
            $je->param1 = $item->status->num;
            $je->insert();
        }
        else
            $error='Ошибка! Не найден город '.$_REQUEST['id'].'';

        $json['error'] = $error;
        $json['status'] = $statusToBe;

        echo json_encode($json);
    }


    public function cityEditForm()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);
        $CORE->setLayout(null);

        if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
        {
            if($id = intval($_REQUEST['id']))
            {
                $MODEL['item'] = City::get($id);
                if(isset($_REQUEST['id']) && !$MODEL['item'])
                    $MODEL['error'] = 'Ошибка! Город не найден. ';
            }
            $MODEL['countries'] = Country::getList();
            $MODEL['regionsDict'] = Region::getList();

            $MODEL['currentCountryId'] = $_REQUEST['currentCountryId'];
            $MODEL['currentRegionId'] = $_REQUEST['currentRegionId'];
        }
        else
            $MODEL['error'] = Error::NO_ACCESS_ERROR;


        Core::renderView('geo/cityEdit.php', $MODEL);
    }


    public function cityEditSubmit()
    {
        require(GLOBAL_VARS_SCRIPT_FILE_PATH);
        Startup::execute(Startup::ADMIN);
        $CORE->setLayout(null);

        if($ADMIN->hasRole(Role::SYSTEM_ADMINISTRATOR) )
        {
//            vd($_REQUEST);
            $item = City::get($_REQUEST['id']);

            $byName = City::getList2(['name'=>$_REQUEST['name'], ]);
            $byName = $byName[0];

            if($byName && ( ($item && $item->id!=$byName->id) || !$item->id)  )
                $errors[] = Slonne::setError('name', 'Такой город уже существует!');
            else
            {
                if(!$item)
                {
                    $item = new City();
                    $item->status = Status::code(Status::ACTIVE);
                }

                $item->setDataFromArray($_REQUEST);
//                vd($item);

                $region = Region::get($item->regionId);
                $item->countryId = $region->countryId;

                $validateResult = $item->validate();
                $errors = array_merge($errors ? $errors : [], $validateResult ? $validateResult : []);
//                vd($errors);

                #   сохраняем
                if(!$errors)
                {
                    if($item->id)
                        $item->update();
                    else
                        $item->insert();
                }
            }
        }
        else
            $errors[] = Slonne::setError(Error::NO_ACCESS_ERROR);


        $res = [
            'errors'=>$errors,
        ];
        vd($res);
        echo '<script>window.top.Geo.City.editSubmitComplete('.json_encode($res).')</script>';
    }
	
}




?>