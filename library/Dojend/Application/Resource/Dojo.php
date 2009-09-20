<?php
class Dojend_Application_Resource_Dojo extends Zend_Application_Resource_ResourceAbstract
{    
    
    /**
     * Container for Dojo view helper
     *
     * @var Zend_Dojo_View_Helper_Dojo_Container
     */
    protected $_container = null;
    
    public function init()
    {
        $options = $this->getOptions();
        
        $container = $this->getContainer();

        Dojend_Debug::pr($options);
        Zend_Dojo_View_Helper_Dojo::setUseDeclarative();
        $container->setDjConfig($options['djConfig']);
        $container->setCdnVersion($options['cdnVersion']);
        //$container->setLocalPath($options['localPath']);
        $container->addStyleSheetModule('dijit.themes.tundra');
        $container->requireModule($options['requireModule']);
        $container->enable();
        
        return $container;
    }
    
    /**
     * Getter for return container
     *
     * @return Zend_Dojo_View_Helper_Dojo_Container
     */
    public function getContainer()
    {
        if(null === $this->_container) {
            $this->getBootstrap()->bootstrap('view');
            $view = $this->getBootstrap()->getResource('view');
            
            Zend_Dojo::enableView($view);
            
            $this->setContainer($view->dojo());            
        }
        
        return $this->_container;
    }
    
    public function setContainer($container)
    {
        $this->_container = $container;
    }
}
