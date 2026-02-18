<?php

class Page_Block_Head extends Core_Block_Template
{
    protected $_js = [];

    public function getJs()
    {
        return $this->_js;
    }
    public function addJs($file)
    {
        $this->_js[] = $file;
        return $this;
    }

    public function __construct()
    {
        $this->setTemplate("Page/View/head.phtml");
        $this->addJs("js/defualt.js")
            ->addJs("defualt1.js");

    }
}

?>