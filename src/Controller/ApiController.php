<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

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
		$users = $this->getDoctrine()->getRepository(User::class)->findAllWithoutSecurity();

		$serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
		
		return new Response($serializer->serialize($users, 'json'));
	}

	/**
	 * @Route("/read/{id}", name="show")
	 */
	public function show(int $id): Response
	{
		$user = $this->getDoctrine()->getRepository(User::class)->findOneWithoutSecurity($id);

		$serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
		
		return new Response($serializer->serialize($user, 'json'));
	}
}
