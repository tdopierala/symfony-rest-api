<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', TextType::class, [
				'required' => false,
				'label' => 'Username',
				'attr' => []
			])
			->add('email', EmailType::class, [
				'required' => true,
				'attr' => []
			])
			->add('password', RepeatedType::class, [
				'required' => true,
				'type' => PasswordType::class,

				'invalid_message' => 'The password fields must match.',
				'options' => ['attr' => ['class' => 'password-field']],
				
				'first_options'  => ['label' => 'Password'],
				'second_options' => ['label' => 'Repeat Password'],
			])
		;

		$builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
			
			$user = $event->getData();
			$form = $event->getForm();

			if (!$user || $user->getId() === null)
			{
				$form
					->add('save', SubmitType::class, [
						'label' => 'Add new user',
						'attr' => ['class' => 'btn-secondary btn-block']
					])
				;
			}
			else
			{
				$form
					->add('token', TextType::class, [
						'required' => true,
						'disabled' => true,
						'attr' => ['aria-describedby' => 'refresh-token']
					])
					->add('save', SubmitType::class, [
						'label' => 'Update data',
						'attr' => ['class' => 'btn-secondary btn-block']
					])
				;
			}
		});
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
