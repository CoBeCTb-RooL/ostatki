<?php
class Region{

    const TBL = 'adv__region_regions';

    var   $id
    , $countryId
    , $name
    , $status
    , $dateCreated
    ;



    function init($arr)
    {
        $m = new self();

        $m->id = $arr['id'];
        $m->countryId = $arr['countryId'];
        $m->name = $arr['name'];
        $m->status = Status::num($arr['status']);
        $m->dateCreated = $arr['dateCreated'];

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
//        vd($params);
        if(isset($params['status']))
            $sql .= " AND status = '".$params['status']->num."' ";

        if($params['id'])
            $sql.=" AND id=".$params['id']."";
        if($params['countryId'])
            $sql.=" AND countryId=".intval($params['countryId'])."";
        if(isset($params['name']))
            $sql.=" AND name='".strPrepare($params['name'])."'";

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
		SET dateCreated=NOW(),
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
		, `countryId`='".intval($this->countryId)."'
		, `status`='".intval($this->status->num)."'
		";

        return $str;
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




    public function setDataFromArray($arr)
    {
        $this->name = $arr['name'];
        $this->countryId = $arr['countryId'];
//        vd($this);
    }



    function validate()
    {
        if(!trim($this->name))
            $problems[] = Slonne::setError('name', 'Введите название!');
        if(!intval($this->countryId))
            $problems[] = Slonne::setError('countryId', 'Укажите страну!');

        return $problems;
    }





    function initCountry()
    {
        $this->country = Country::get($this->countryId);
    }





    public function cities($citiesDict=null)
    {
        $ret = null;

        $citiesDict = $citiesDict ? $citiesDict : City::getList2(['regionId'=>$this->id, ]);

        foreach ($citiesDict as $city)
            if($city->regionId == $this->id)
                $ret[] = $city;

        return $ret;
    }



}
?>