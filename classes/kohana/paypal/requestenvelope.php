<?php

/**
 * Abstract PayPal integration.
 *
 * @link  https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/library_documentation
 * 
 * @package PayPal
 * @author     Guillaume Poirier-Morency
 * @copyright  Hète.ca Inc.
 * @license    http://kohanaphp.com/license.html
 */
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
