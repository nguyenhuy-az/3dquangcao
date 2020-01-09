<?php
/**
 * Created by PhpStorm.
 * User: HUY
 * Date: 11/10/2016
 * Time: 12:54 PM
 */
#namespace app\Includes\notify\map\land;



class LandNotify extends  Hfunction
{
    #private $keyWord = array();

    public function set()
    {
        $keyWord = [
            'exist select free' => 'Sorry, You already use the free features, You can use features other'
        ];
        return $keyWord;
    }

    public function get($keyword)
    {
        $result = '';
        $notify = $this->set();
        foreach($notify as $key => $value){
            if($key == $keyword ) $result = $value;
        }
        return (empty($result))? $keyword: $result;
    }
}

?>