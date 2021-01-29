<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiTokenAuthenticator extends AbstractGuardAuthenticator
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function supports(Request $request)
	{
		return $request->headers->has('X-AUTH-TOKEN');
	}

	public function getCredentials(Request $request)
	{
		return $request->headers->get('X-AUTH-TOKEN');
	}

	public function getUser($credentials, UserProviderInterface $userProvider)
	{
		if (null === $credentials) {
			// The token header was empty, authentication fails with HTTP Status
			// Code 401 "Unauthorized"
			return null;
		}

		return $userProvider->loadUserByUsername($credentials);
	}

	public function checkCredentials($credentials, UserInterface $user)
	{
		return true;
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
	{
		return null;
	}

	public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
	{
		$data = [
			'message' => strtr($exception->getMessageKey(), $exception->getMessageData()),
		];

		return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
	}

	public function start(Request $request, AuthenticationException $authException = null)
	{
		$data = [
			'message' => 'Authentication Required'
		];

		return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
	}

	public function supportsRememberMe()
	{
		return false;
	}
}
