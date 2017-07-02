<?php

class IndexController extends ControllerBase
{

    public function indexAction()
    {
        if ($this->session->has('id')) {
            $this->response->redirect('/user/list');
            return;
        }
    }
}

