<?php
class AdminActivityController extends MainController{
	
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
		
		Core::renderView('adminActivity/indexView.php', $model);
	}
	
	
	
	
	function list1()
	{
		require(GLOBAL_VARS_SCRIPT_FILE_PATH);
		Startup::execute(Startup::ADMIN);
		
		$CORE->setLayout(null);
		
		//vd($ADMIN);
		if($ADMIN->hasRole(Role::ADMINS_MODERATOR))
		{
		    $params = [];
			$MODEL['list'] = JournalEntry::getList($params);
			foreach($MODEL['list'] as $key=>$val)
				$val->initAdmin();
		}
		else 
			$MODEL['error'] = Error::NO_ACCESS_ERROR;
		
		Core::renderView('adminActivity/list.php', $MODEL);
	}
	

	
	
	
	
}




?>