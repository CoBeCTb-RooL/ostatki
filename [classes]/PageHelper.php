<?
class PageHelper{

    public $page;
    public $elPP;
    public $totalCount;
    public $pagesCount;


    function __construct($page, $elPP, $totalCount)
    {
        $this->page = intval($page) ? intval($page) : 1;
        $this->elPP = $elPP;
        $this_>$this->totalCount = $totalCount;
        $this->pagesCount = $this->pagesCount();
    }

    function pagesCount()
    {
        return ceil($this->totalCount / $this->elPP);
    }


    function infoStr()
    {

        $ret = ''.(($this->page-1)*$this->elPP+1).'-'.($this->elPP < $this->totalCount ? (($this->page-1)*$this->elPP+$this->elPP) : $this->totalCount).' из '.$this->totalCount.'';

        return $ret;
    }





    function html($params)
    {
        //vd($onclick);
//        $class = $class ? $class : 'pages';
        $class = $params['class'] ? $params['class'] : 'pages';
        $sym = $params['symbols'];
        //vd($currentPage);
        $sym['beginning'] = $sym['beginning'] ? $sym['beginning'] : 'начало <<';
        $sym['prev'] = $sym['prev'] ? $sym['prev'] : '&larr; предыдущие';

        $sym['end'] = $sym['end'] ? $sym['end'] : '>> конец';
        $sym['next'] = $sym['next'] ? $sym['next'] : 'следующие &rarr;';

//        $this->pagesCount=ceil($totalCount/$elPP);
        $str = '';
        if($this->pagesCount > 1)
        {
            $currentPage = $this->page - 1;
            $onclick = $params['onclick'];
            $str.='
		<div class="'.$class.'">';

            if($currentPage>0)
            {
                $str .= '
        	<a class="item beginning"  title="начало"  href="#page-1" onclick="'.$this->getOnclick(1, $onclick).'; return false; ">'.$sym['beginning'].'</a>
        	<a class="item" title="предыдущая" href="#page-'.$currentPage.'" onclick="'.$this->getOnclick( ($currentPage), $onclick).'; return false; ">'.$sym['prev'].'</a>';
            }
            else
            {
                $str.='
        	<div class="item inactive beginning">'.$sym['beginning'].'</div>
        	<div class="item inactive">'.$sym['prev'].'</div>';
            }

            $index = $currentPage>=6 ? ($currentPage+1<$this->pagesCount-5 ? $currentPage-5 : ($this->pagesCount>11 ? $this->pagesCount-11 : 0)) : 0;
            for($i=1; $i<= ($this->pagesCount<11 ? $this->pagesCount : 11); $i++)
            {
                $index++;
                if($index>$this->pagesCount) break;
                if($index == $currentPage+1)
                {
                    $str .= ' <div class="item current">'.$index.'</div> ';
                }
                else
                {
                    $str .= '<a class="item" href="#page-'.$index.'" onclick="'.$this->getOnclick($index, $onclick).'; return false; ">'.$index.'</a> ';
                }
            }

            if($currentPage+1 < $this->pagesCount)
            {
                $str .= '
        	<a class="item" title="следующая"  href="#page-'.($currentPage+2).'" onclick="'.$this->getOnclick( ($currentPage+2), $onclick).'; return false; ">'.$sym['next'].'</a>
        	<a class="item end" title="конец"  href="#page-'.$this->pagesCount.'" onclick="'.$this->getOnclick($this->pagesCount, $onclick).'; return false; ">'.$sym['end'].'</a>';
            }
            else
            {
                $str.='
        	<div class="item inactive">'.$sym['next'].'</div>
        	<div class="item inactive end">'.$sym['end'].'</div>';
            }

            $str.='
		</div>';
            $str.='
		<div style="clear: both; "></div>';
        }


        return  $str;
    }






    function html2($params)
    {
        $onclick = $params['onclick'];


        if($this->pagesCount > 1)
        {
            ob_start();
        ?>

        <style>
            .pages{}
            .pages .disabled { pointer-events: none; cursor: default; opacity: 0.5; }
            .pages a{display: inline-block; padding: 0 7px; }

        </style>

        <div class="pages">
            <a href="#" class="<?=$this->page <= 1 ? 'disabled' : ''?>" onclick="<?=$this->getOnclick(1, $onclick)?>" ><<начало </a>
            <a href="#" class="<?=$this->page <= 1 ? 'disabled' : ''?>" onclick="<?=$this->getOnclick($this->page-1, $onclick)?>" >&larr; пред</a>
            стр.
            <select onchange="<?=$this->getOnclick('$(this).val()', $onclick)?>">
                <?for($i=1; $i<=$this->pagesCount; $i++):?>
                <option <?=$this->page == $i ? ' selected ' : ''?> ><?=$i?> из <?=$this->pagesCount?></option>
                <?endfor?>
            </select>
            <a href="#" class="<?=$this->page >= $this->pagesCount ? 'disabled' : '' ?>" onclick="<?=$this->getOnclick($this->page+1, $onclick)?>" >след &rarr;</a>
            <a href="#" class="<?=$this->page >= $this->pagesCount ? 'disabled' : '' ?>" onclick="<?=$this->getOnclick($this->pagesCount, $onclick)?>" >конец>></a>
        </div>
            <?
            $str .= ob_get_clean();
        }

        return $str;
    }








    function getOnclick($page, $onclick)
    {
        $str=str_replace("###", $page, $onclick);

        return $str;
    }




}