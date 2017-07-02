<?php

/**
 * Created by PhpStorm.
 * User: Hatsu
 * Date: 02.07.2017
 * Time: 19:09
 */
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Http\Request;

class UserController extends \Phalcon\Mvc\Controller
{
    protected function getMongo()
    {
        return new MongoDB\Driver\Manager("mongodb://localhost:27017");
    }


    public function registerAction()
    {
        $request = new Request();
        $validationResult = $this->registerValidation($request);
        if($validationResult !== true)
        {
            foreach ($validationResult as $r)
            {
                echo $r->getMessage().'<br>';
            }
            return;
        }

        $mongo = $this->getMongo();

        $filter = ['email' => $request->getPost('email')];
        $query = new MongoDB\Driver\Query($filter);
        $rows = $mongo->executeQuery('test.users', $query);
        $userExist = $rows->toArray() ?? false;
        if($userExist)
        {
            echo 'Пользователь с таким Email уже зарегистирирован';
            return;
        }
        $password = $this->security->hash($request->getPost('password'));

        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->insert(['name'=>$request->getPost('name'),'email'=>$request->getPost('email'),'password'=>$password]);
        $result = $mongo->executeBulkWrite('test.users',$bulk);
        if(!$result)
        {
            echo 'Ошибка';
            return;
        }
        $rows = $mongo->executeQuery('test.users', $query);
        $userExist = $rows->toArray() ?? false;
        if(!$userExist)
        {
            echo 'Ошибка бд';
            return;
        }
        $this->session->set('id', $userExist[0]->id);
        $this->session->set('name', $request->getPost('name'));
        $this->session->set('email', $request->getPost('email'));
        $this->response->redirect('/user/list');
        $this->view->disable();
        return;
    }

    public function listAction()
    {
        $mongo = $this->getMongo();
        $filter = [];
        $query = new MongoDB\Driver\Query($filter);
        $rows = $mongo->executeQuery('test.users', $query);
        $this->view->list = $rows->toArray();
    }

    protected function registerValidation(Phalcon\Http\Request $request)
    {
        $validation = new Validation();
        $validation->add('name', new PresenceOf(['message'=>'Имя должно быть заполненно']));
        $validation->add('email',new PresenceOf(['message'=>'Email должен быть заполнен']));
        $validation->add('email',new Email(['message'=>'Email веден не верно']));
        $validation->add('password',new PresenceOf(['message'=>'Пароль должен быть заполнен']));
        $messages = $validation->validate($request->getPost());
        if(count($messages) === 0)
        {
            return true;
        }else{
            return $messages;
        }
    }
    public function logoutAction()
    {
        $this->session->destroy();
        $this->response->redirect('/');
        $this->view->disable();
    }


    public function loginAction()
    {
        $request = new Phalcon\Http\Request();
        $validation = new Validation();
        $validation->add('email',new PresenceOf(['message'=>'Email должен быть заполнен']));
        $validation->add('email',new Email(['message'=>'Email веден не верно']));
        $validation->add('password',new PresenceOf(['message'=>'Пароль должен быть заполнен']));
        $messages = $validation->validate($request->getPost());
        if(count($messages) !== 0)
        {
            foreach ($messages as $r)
            {
                echo $r->getMessage().'<br>';
            }
            return;
        }

        $mongo = $this->getMongo();
        $filter = ['email'=>$this->request->getPost('email')];
        $query = new MongoDB\Driver\Query($filter);
        $rows = $mongo->executeQuery('test.users', $query);

        $user = $rows->toArray()[0] ?? false;
        if(!$user)
        {
            echo 'Не верно указана почта или пароль';
            return;
        }
        $checkPasswordResult = $this->security->checkHash($this->request->getPost('password'),$user->password);
        if(!$checkPasswordResult)
        {
            echo 'Не верно указана почта или пароль';
            return;
        }
        $this->session->set('id', $user->id);
        $this->session->set('name', $user->name);
        $this->session->set('email', $user->email);
        $this->response->redirect('/user/list');
        $this->view->disable();
    }
}