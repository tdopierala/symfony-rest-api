<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

	/**
	 * @Route("/api", name="api_user_", methods={"GET","HEAD"})
	 */
class ApiController extends AbstractController
{
	/**
	 * @Route("/index", name="index")
	 */
	public function index(): Response
	{
		$users = $this->getDoctrine()->getRepository(User::class)->findBy([],['id' => 'DESC']);

		//$serializer->serialize($users,'json');
		
		return new JsonResponse($this->serializer->normalize($users, null));
	}
}
