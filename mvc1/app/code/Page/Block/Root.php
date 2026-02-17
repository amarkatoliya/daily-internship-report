<?php

class Page_Block_Root extends Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("Page/View/root.phtml");
    }

    public function _construct() {
        $head = sdp::getBlock("page/head");
        $header = sdp::getBlock("page/header");
        $content = sdp::getBlock("page/content");
        $footer = sdp::getBlock("page/footer");
        $this->addChild("head",$head);
        $this->addChild("header",$header);
        $this->addChild("content",$content);
        $this->addChild("footer",$footer);
    }
}

?>