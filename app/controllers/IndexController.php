<?php

class IndexController extends \BaseController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadResourcesForInfoPage();
        $this->setCommonVariables();
    }

    private function setCommonVariables()
    {
        $this->view->setVars([
            'page_cache_secs' => $this->di->get('config')->page_cache_secs
        ]);
    }

    private function loadResourcesForInfoPage()
    {
        // Add some local CSS resources
        // $this->assets->addCss(STYLE_PREFIX . 'style.css');
        // $this->assets->addCss(STYLE_PREFIX . 'main.css');

        // And some local JavaScript resources
        // $this->assets->addJs(VENDOR_PREFIX . 'jquery/dist/jquery.min.js');
        // $this->assets->addJs(VENDOR_PREFIX . 'jquery-ui/jquery-ui.min.js');
        // $this->assets->addJs(VENDOR_PREFIX . 'bootstrap/dist/js/bootstrap.min.js');
    }

    public function indexAction()
    {
        $cacheKey = __FUNCTION__;
        $this->view->setVars([
            'page_cache_key' => $cacheKey,
        ]);
    }

    public function unAuthorizedAction()
    {
        return $this->responseMessage(
                $this->dispatcher->getParam('messages'),
                $this->dispatcher->getParam('status')
            );
    }

    public function route404Action()
    {
        return $this->responseMessage([PAGE_NOT_FOUND], NOT_FOUND);
    }
}
