<?php

/**
 * 
 */
class Kohana_PayPal_Response extends Validation {

    /**
     * 
     * @param array $data
     * @return \PayPal_Response
     */
    public static function factory(array $data, $redirect_url = NULL) {
        return new PayPal_Response($data, $redirect_url);
    }

    /**
     *
     * @var type 
     */
    public $redirect_url;

    public function __construct(array $data, $redirect_url = NULL) {

        // Sanitize data with dots
        array_walk_recursive($data, array($this, "sanitize"));
        
        $this->_redirect_url = $redirect_url;

        // Building validation object
        parent::_construct($data);
    }

    /**
     * 
     * @param string $input
     * @return string
     */
    public function sanitize($input) {
        return str_replace("_", ".", $input);
    }

}

?>
