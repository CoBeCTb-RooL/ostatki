<?
class GeoHelper{

    public $city;
    public $region;
    public $country;

    private static $_countries;
    private static $_regions;
    private static $_cities;


    public function __construct($cityId)
    {
        if($cityId)
        {
            $this->city = self::city($cityId);
            if($this->city)
            {
                $this->region = self::region($this->city->regionId);
                if($this->region)
                    $this->country = self::country($this->region->countryId);
            }
        }
    }


    public static function countries($status = Status::ACTIVE)
    {
        if(!count(self::$_countries))
        {
            $statusObj = Status::code($status);
            self::$_countries = Country::getList(['status'=>$statusObj, ]);
        }
        $ret = self::$_countries;

        return $ret;
    }
    public static function country($countryId)
    {
        $ret = null;
        if($countryId)
            $ret = self::countries()[$countryId];
        return $ret;
    }


    public static function regions($status = Status::ACTIVE)
    {
        if(!count(self::$_regions))
        {
            $statusObj = Status::code($status);
            self::$_regions = Region::getList(['status'=>$statusObj, ]);
        }
        $ret = self::$_regions;

        return $ret;
    }
    public static function region($regionId)
    {
        $ret = null;
        if($regionId)
            $ret = self::regions()[$regionId];
        return $ret;
    }


    public static function cities($status = Status::ACTIVE)
    {
        if(!count(self::$_cities))
        {
            $statusObj = Status::code($status);
            self::$_cities = City::getList2(['status'=>$statusObj, ]);
        }
        $ret = self::$_cities;

        return $ret;
    }
    public static function city($cityId)
    {
        $ret = null;
        if($cityId)
            $ret = self::cities()[$cityId];
        return $ret;
    }





}