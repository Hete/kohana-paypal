<?php

class Kohana_PayPal_RequestEnvelope extends PayPal_Object {

    public $errorLanguage;
    public $detailLevel;

    public function encode() {
        return array(
            "errorLanguage" => $this->errorLanguage,
            "detailLevel" => $this->detailLevel,
        );
    }

    public function rules() {
        return array(
            'requestEnvelope_errorLanguage' => array(
                array('not_empty')
            )
        );
    }

}

?>
