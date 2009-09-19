<?php
class Dojend_Application_Resource_Dojo extends Zend_Application_Resource_ResourceAbstract
{    
    public function init()
    {
        $options = $this->getOptions();
        $this->getBootstrap()->bootstrap('view');
        $view = $this->getBootstrap()->getResource('view');
        
        Zend_Dojo::enableView($view);

        Dojend_Debug::pr($options['djConfig']);
        $view->dojo()->setDjConfig($options['djConfig'])                     
                     ->enable();                     
        return '';
    }    
}
