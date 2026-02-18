<?php

class Core_Model_Abstact
{
    protected $_data = [];

    public function __set($key,$value) {
        $this->_data[$key] = $value;
        return $this;
    }

    public function __get($key) {
        return $this->_data[$key];
    }
    
    public function addData($data = [])
    {
        $this->_data = $data;
        return $this;
    }
}
