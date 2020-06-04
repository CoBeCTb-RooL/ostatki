<?php 
class Country{
	
	const TBL = 'adv__region_countries';
	
	const KAZAKHSTAN_ID = 1;
	const RUSSIA_ID = 2;

	var   $id
		, $name
		, $status
		;
		
	
		
	function init($arr)
	{
		$m = new self();
		
		$m->id = $arr['id'];
		$m->name = $arr['name'];
		$m->status = Status::num($arr['status']);

		return $m;
	}
	
	
	function getList($params)
	{
		$sql = "SELECT * FROM `".self::TBL."` WHERE 1 ".self::getListInnerSql($params)." ";
//		vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
		while($next = mysql_fetch_array($qr, MYSQL_ASSOC))
        {
			$res[] = self::init($next);
        }
//        vd($res);
		
		return $res;
	}



    function getListInnerSql($params)
    {
        //vd($params);
        if(isset($params['status']))
            $sql .= " AND status = '".$params['status']->num."' ";

        if($params['id'])
            $sql.=" AND id=".$params['id']."";
        if($params['name'])
            $sql.=" AND name='".$params['name']."'";

        $sql.="		
		".($params['orderBy'] ? " ORDER BY ".($params['orderBy'] ? strPrepare($params['orderBy']) : 'id') : "")."
		".strPrepare($params['limit'])."
		";

        return $sql;
    }
	
	
	
	function get($id)
	{
		if($id =intval($id))
		{
			$sql = "SELECT * FROM `".self::TBL."` WHERE id = ".$id;
			$qr=DB::query($sql);
			echo mysql_error();
			if($next = mysql_fetch_array($qr, MYSQL_ASSOC))
				return self::init($next);
		}
	}
	
	
	
	

	function insert()
	{
		$sql = "
		INSERT INTO `".self::TBL."` 
		SET 
		".$this->alterSql()."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();

        $this->id = mysql_insert_id();
        return $this->id;
	}
	

	
	
	function update()
	{
		$sql = "
		UPDATE `".self::TBL."` 
		SET 
		".$this->alterSql()."
		WHERE id=".intval($this->id)."
		";
		//vd($sql);
		$qr=DB::query($sql);
		echo mysql_error();
	}
	
	
	
	
	function alterSql()
	{
		$str.="
		  `name`='".strPrepare($this->name)."'
		, `status`='".strPrepare($this->status->num)."'
		";
		
		return $str;
	}



	public function setDataFromArray($arr)
    {
        $this->name = $arr['name'];
    }
	
	
	
	
	
	function delete($id)
	{
		if($id = intval($id))
		{
			$sql = "
			DELETE FROM `".self::TBL."` WHERE id=".$id;
			DB::query($sql);
			echo mysql_error(); 
		}
	}
	
	
	
	
	
	function validate()
	{
		if(!trim($this->name))
			$problems[] = Slonne::setError('name', 'Введите название!');
		
		return $problems;
	}
	



	public function regions($regionsDict=null)
    {
        $ret = null;

        $regionsDict = $regionsDict ? $regionsDict : Region::getList(['countryId'=>$this->id, ]);

        foreach ($regionsDict as $region)
            if($region->countryId == $this->id)
                $ret[] = $region;

        return $ret;
    }
	
	
	
}
?>