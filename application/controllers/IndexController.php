<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
        $some = new Default_Model_Some();
        
        Dojend_Debug::vd($some);
    }


}

