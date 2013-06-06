<?php

namespace Message\Mothership\ControlPanel\Controller;

class Authentication extends \Message\Cog\Controller\Controller
{
	protected $_redirectRoute = 'ms.cp.dashboard';

	public function login()
	{
		if ($this->get('user.current') instanceof User) {
			$this->redirect($this->generateUrl($this->_redirectRoute));
		}

		return $this->render('::login', array(
			'redirectRoute' => $this->_redirectRoute,
		));
	}

	public function logout()
	{
		return $this->forward('Message:User::Controller:Authentication#logoutAction', array(
			'redirectURL' => $this->generateUrl('ms.cp.login')
		));
	}
}