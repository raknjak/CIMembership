<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

abstract class OAuth2_Abstract
{

    protected $_provider;               // will contain the built authorization URL object
    protected $_scope = 'email';        // scope depends on provider, so it is configured in the providers but is always needed

    public function __construct() {}

    public function loadProviderClass($data) {
        $this->setProvider($data);

        return $this->_provider->getAuthorizationUrl([
            'scope' => [$this->_scope]
        ]);
    }

    abstract protected function setProvider($data);

    // get the loaded provider authorization url object
    public function getProvider() {
        return $this->_provider;
    }

    // get the provider state
    public function getState() {
        return $this->_provider->getState();
    }

}