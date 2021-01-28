<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
	/**
	 * @Route("/login", name="app_login")
	 */
	public function login(AuthenticationUtils $authenticationUtils): Response
	{
		// if ($this->getUser()) {
		//     return $this->redirectToRoute('target_path');
		// }

		// get the login error if there is one
		$error = $authenticationUtils->getLastAuthenticationError();
		// last username entered by the user
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
	}

	/**
	 * @Route("/logout", name="app_logout")
	 */
	public function logout()
	{
		throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
	}

	/**
	 * @Route("/register", name="app_register")
	 */
	public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
	{
		$em = $this->getDoctrine()->getManager();
		$user = new User();
		
		$form = $this->createForm(UserFormType::class, $user);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$user->setPassword(
				$passwordEncoder->encodePassword(
					$user, 
					$form->get('password')->getData()
				)
			);

			$em->persist($user);
			$em->flush();
			
			return $this->redirectToRoute('app_login');
		}
		else
		{
			return $this->render('user/create.html.twig', [
				'form' => $form->createView(),
			]);
		}
	}
}
