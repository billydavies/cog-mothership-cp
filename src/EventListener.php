<?php

namespace Message\Mothership\ControlPanel;

use Message\Mothership\ControlPanel\Event\BuildMenuEvent;

use Message\User\AnonymousUser;

use Message\Cog\Event\EventListener as BaseListener;
use Message\Cog\Event\SubscriberInterface;
use Message\Cog\HTTP\RedirectResponse;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Event listener for the Mothership Control Panel.
 *
 * @author Joe Holdcroft <joe@message.co.uk>
 */
class EventListener extends BaseListener implements SubscriberInterface
{
	/**
	 * {@inheritDoc}
	 */
	static public function getSubscribedEvents()
	{
		return array(
			KernelEvents::EXCEPTION => array(
				array('sendToLogin'),
				array('renderErrorPage'),
			),
		);
	}

	/**
	 * Send the user to the login page if the exception relates to a HTTP 403
	 * status code, user is not currently logged in and the request matched a
	 * route in the 'ms.cp' collection.
	 *
	 * @param GetResponseForExceptonEvent $event The event object
	 */
	public function sendToLogin(GetResponseForExceptionEvent $event)
	{
		// Skip if the user is logged in
		if (!($this->_services['user.current'] instanceof AnonymousUser)) {
			return false;
		}

		// If it's an access denied exception, send the user to the login page
		if ($event->getException() instanceof HttpException
		 && 403 === $event->getException()->getStatusCode()
		 && is_array($event->getRequest()->get('_route_collections'))
		 && in_array('ms.cp', $event->getRequest()->get('_route_collections'))) {
			$event->setResponse(new RedirectResponse(
				$this->_services['routing.generator']->generate('ms.cp.login')
			));
		}
	}

	/**
	 * Render a control panel error page for the exception.
	 *
	 * @param GetResponseForExceptonEvent $event The event object
	 */
	public function renderErrorPage(GetResponseForExceptionEvent $event)
	{
		// Skip if working locally
		if ('local' === $this->get('env')) {
			return false;
		}

		// Skip if the requested route is not in the control panel
		if (!is_array($event->getRequest()->get('_route_collections')) || !in_array('ms.cp', $event->getRequest()->get('_route_collections'))) {
			return false;
		}

		$request = $event->getRequest()->duplicate(null, null, array(
			'_controller' => 'Message\\Mothership\\ControlPanel\\Controller\\Error::exception',
			'_format'     => $event->getRequest()->getRequestFormat(),
			'exception'   => $event->getException(),
		));

		$request->setMethod('GET');

		$response = $event->getKernel()->handle($request, HttpKernelInterface::SUB_REQUEST, true);

		$event->setResponse($response);
	}
}