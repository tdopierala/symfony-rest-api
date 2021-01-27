<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	/**
	 * @Route("/", name="user")
	 */
	public function index(): Response
	{
		$users = $this->getDoctrine()->getRepository(User::class)->findAll();

		return $this->render('user/index.html.twig', [
			'users' => $users,
		]);
	}
}
