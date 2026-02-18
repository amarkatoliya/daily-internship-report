<?php

class Page_Block_Menu extends Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("Page/View/menu.phtml");
    }

    public function getMenuArray() {
        return array(
            "url1"=> "category 1",
            "url2"=> "category 2"
        );
    }
}

?>