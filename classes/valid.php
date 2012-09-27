<?php

class Valid extends Kohana_Valid {

    public static function boolean(string $str) {
        $str = (string) $str;
        return $str === "true" | $str === "false";
    }

    public static function date($str, $format = NULL) {
        $time = strtotime($str);
        
        if($format !== NULL && $time !== FALSE && date($format, $time) === $str) {
            return parent::date($str);            
        }
        
        return parent::date($str);
    }

}

?>
