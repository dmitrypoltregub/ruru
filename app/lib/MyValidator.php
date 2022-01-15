<?php

namespace App\Lib;

class MyValidator
{
    static public function luna_check($s)
    {
        $s = strrev(preg_replace('/[^\d]/','',$s));
        $sum = 0;
        for ($i = 0, $j = strlen($s); $i < $j; $i++)
        {
            if (($i % 2) == 0) {
                $val = $s[$i];
            } else {
                $val = $s[$i] * 2;
                if ($val > 9)  $val -= 9;
            }
            $sum += $val;
        }

        return (($sum % 10) == 0);
    }

    static public function cvc_check($number)
    {
        $check = false;
        if(is_numeric($number))
        {
            $numlength = strlen((string)$number);
            if($numlength == 3)
                $check = true;
        }

        return $check;
    }

    static public function date_checker($date)
    {
        $check = false;
        $arDate = explode('/', $date);
        if(count($arDate)==2)
        {
            foreach($arDate as $key=>$itemDate)
            {
                if(is_numeric($itemDate))
                {
                    $numlength = strlen((string)$itemDate);
                    if($numlength != 2)
                        break;
                }
                if($key == 1)
                  $check = true;
            }
        }
        return $check;
    }
}
