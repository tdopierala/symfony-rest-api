<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	/**
	 * @Route("/", name="user_index")
	 */
	public function index(): Response
	{
		$users = $this->getDoctrine()->getRepository(User::class)->findBy([],['id' => 'DESC']);

		return $this->render('user/index.html.twig', [
			'users' => $users,
		]);
	}

	/**
	 * @Route("/create", name="user_create")
	 */
	public function create(Request $request): Response
	{
		$em = $this->getDoctrine()->getManager();
		$user = new User();

		$form = $this->createForm(UserFormType::class, $user);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			//dump($form->getData());

			$em->persist($user);
			$em->flush();
			
			return $this->redirectToRoute('user_index');
		}
		else
		{
			return $this->render('user/create.html.twig', [
				'form' => $form->createView(),
			]);
		}
	}

	/**
	 * @Route("/profile/{id}", name="user_profile")
	 */
	public function show(int $id): Response
	{
		$user = $this->getDoctrine()->getRepository(User::class)->find($id);

		return $this->render('user/show.html.twig', [
			'user' => $user,
		]);
	}

	/**
	 * @Route("/edit/{id}", name="user_edit")
	 */
	public function edit(Request $request, int $id): Response
	{
		$em = $this->getDoctrine()->getManager();
		$user = $this->getDoctrine()->getRepository(User::class)->find($id);

		$form = $this->createForm(UserFormType::class, $user);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			//dump($form->getData());

			$em->persist($user);
			$em->flush();
			
			return $this->redirectToRoute('user_index');
		}
		else
		{
			return $this->render('user/edit.html.twig', [
				'form' => $form->createView(),
				'user' => $user,
			]);
		}
	}

	/**
	 * @Route("/delete/{id}", name="user_delete")
	 */
	public function destroy(int $id): Response
	{
		$user = $this->getDoctrine()->getRepository(User::class)->find($id);

		$em = $this->getDoctrine()->getManager();
		$em->remove($user);
		$em->flush();

		return $this->redirectToRoute('user_index');
	}

	/**
	 * @Route("/activate/{id}", name="user_activate")
	 */
	public function activate(int $id): Response
	{
		$repository = $this->getDoctrine()->getRepository(User::class);

		$user = $repository->find($id);

		$roles = $user->getRoles();

		if (\array_search('ROLE_API_USER', $roles) === false) {
			\array_push($roles, 'ROLE_API_USER');
		} else {
			\array_splice($roles, \array_search('ROLE_API_USER', $roles), 1);
		}

		$user->setRoles($roles);

		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		return $this->redirectToRoute('user_edit', ['id' => $id]);
	}

	/**
	 * @Route("/token/{id}", name="user_token")
	 */
	public function token(int $id): Response
	{
		$repository = $this->getDoctrine()->getRepository(User::class);

		$user = $repository->find($id);
		
		$user->setToken(\bin2hex(\random_bytes(64)));

		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		return $this->redirectToRoute('user_edit', ['id' => $id]);
	}
}
