<?php
/**
 * User controller
 */

namespace Demo\Controllers;

use Fonto\Core\Controller\Base;
use Demo\Models\Forms;
use Demo\Models;

class User extends Base
{
	public function profileAction()
	{
		// User NOT logged in? redirect..
		if (!$this->auth()->isAuthenticated()) {
			$this->response()->redirect($this->url()->baseUrl().'user/login');
		}

		$userId = $this->auth()->getUser(); // Gets information from session
		$userId = $userId['id'];

		$user = new Models\User();
		$userInfo = Models\User::find_by_id($userId);
		$url = $this->url();
		$form = $this->form();

		$data = array(
			'baseUrl'  => $url->baseUrl(),
			'form'     => $form,
			'auth'     => $this->auth(),
			'session'  => $this->session(),
			'username' => $userInfo->username,
			'name'     => $userInfo->name,
			'email'    => $userInfo->email
		);

		// Form submitted?
		if ($this->request()->isPost()) {

			// Gets form model for profile
			$formModel = new Forms\Profile;
			$validator = $this->validator();
			// Throws in validation obj
			$formModel->rules($validator);

			// Validates all inputs
			$validator->validate($this->request()->post()->getAll());

			// Validation succeeded?
			if ($validator->isValid()) {

				$passwordCheck = $this->request()->post()->get('password');
				$username      = $this->request()->post()->get('username');
				$password      = $this->phpass()->hashPassword($this->request()->post()->get('password'));
				$email         = $this->request()->post()->get('email');
				$name          = $this->request()->post()->get('name');

				if ($passwordCheck == '') {
					$save = $user->updateRecord($userId, $username, false, $email, $name);
				} else {
					$save = $user->updateRecord($userId, $username, $password, $email, $name);
				}

				if ($save) {
					$this->session()->set('success', 'din profil är nu uppdaterad');
					$this->response()->redirect($this->url()->baseUrl().'user/profile');
				}

			} else {
				$error = array('validator' => $validator);
				$container = $data + $error;
				return $this->view()->render('user/profile', $container);
			}
		}

		return $this->view()->render('user/profile', $data);
	}

	public function loginAction()
	{
		$url  = $this->url();
		$form = $this->form();

		$data = array(
			'baseUrl' => $url->baseUrl(),
			'form'    => $form,
			'auth'    => $this->auth(),
			'session' => $this->session()
		);

		// Form submitted?
		if ($this->request()->isPost()) {

			$username = $this->request()->post()->get('username');
			$password = $this->request()->post()->get('password');

			if ($this->auth()->authenticate($username, $password)) {

				$this->session()->set('success', 'du är nu inloggad!');
				$this->response()->redirect($this->url()->baseUrl().'user/profile');

			} else {

				$this->session()->set('error', 'användarnamnet/lösenordet stämmer inte');
				return $this->view()->render('user/login', $data);

			}

		}

		return $this->view()->render('user/login', $data);
	}

	public function registerAction()
	{
		$url  = $this->url();
		$form = $this->form();

		$data = array(
			'baseUrl' => $url->baseUrl(),
			'form'    => $form,
			'auth'    => $this->auth(),
			'session' => $this->session()
		);

		// Form submitted?
		if ($this->request()->isPost()) {

			// Register form model
			$formModel = new Forms\Register;
			$validator = $this->validator();
			$formModel->rules($validator);

			$validator->validate($this->request()->post()->getAll());

			if ($validator->isValid()) {

				$user = new Models\User();

				$credentials = array(
					'username' => $this->request()->post()->get('username'),
					'password' => $this->phpass()->hashPassword($this->request()->post()->get('password')),
					'email'    => $this->request()->post()->get('email'),
					'name'     => $this->request()->post()->get('name')
				);

				// Creates user
				$userRecord = $user->createRecord($credentials);

				if ($userRecord) {

					$role = new Models\Userrole();

					// Sets user role to regular user
					$credentials = array(
						'user_id' => $userRecord->id,
						'role_id' => 2
					);

					Models\Userrole::create($credentials);

					$this->session()->set('success', 'ditt konto är nu skapat!');
					$this->response()->redirect($this->url()->baseUrl().'user/register');

				} else {
					return $this->view()->render('user/register', $data);
				}

			} else {

				$error = array('validator' => $validator);
				$container = $data + $error;
				return $this->view()->render('user/register', $container);
			}
		}

		return $this->view()->render('user/register', $data);
	}

	public function logoutAction()
	{
		// Logs user out and kills session
		$this->auth()->logout();
		$this->session()->set('success', 'du är nu utloggad');
		$this->response()->redirect($this->url()->baseUrl().'user/login');
	}
}