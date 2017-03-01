<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\EqualValidator;


class MemberFormBuilder extends FormBuilder
{
	public function build()
	{
		$this->form->add(new StringField([
			'label' => 'Nom d\'utilisateur',
			'name' => 'username',
			'maxLength' => 20,
			'validators' => [
				new MaxLengthValidator('L\'username choisi est trop long (20 caractères maximum)', 20),
				new NotNullValidator('Il vous faut un nom d\'utilisateur !'),
			],
		]))
				   ->add(new StringField([
					   'label' => 'Mot de passe',
					   'name' => 'password',
					   'maxLength' => 20,
					   'validators' => [
						   new MaxLengthValidator('Le mot de passe choisi est trop long (20 caractères maximum)', 20),
						   new NotNullValidator('Il vous faut un mot de passe !'),
					   ],
				   ]))
						->add(new StringField([
							'label' => 'Confirmation du mot de passe',
							'name' => 'passwordConfirmation',
							'maxLength' => 20,
							'validators' => [
								new MaxLengthValidator('Le mot de passe choisi est trop long (20 caractères maximum)', 20),
								new NotNullValidator('Veuillez confirmer votre mot de passe.'),
								new EqualValidator('Le mot de passe n\'est pas le même.', 'MOT DE PASSE A RENTRER ICI'),
								],
							]))
						->add(new StringField([
							'label' => 'Adresse e-mail',
							'name' => 'email',
							'maxLength' => 40,
							'validators' => [
								new MaxLengthValidator('L\'adresse e-mail choisie est trop longue (40 caractères maximum)', 40),
								new NotNullValidator('Veuillez entrer votre adresse e-mail.'),
								],
							]))
						->add(new StringField([
							'label' => 'Confirmation de l\'adresse e-mail',
							'name' => 'emailConfirmation',
							'maxLength' => 40,
							'validators' => [
								new MaxLengthValidator('L\'adresse e-mail choisie est trop longue (40 caractères maximum)', 40),
								new NotNullValidator('Veuillez confirmer votre adresse e-mail.'),
								new EqualValidator('L\'adresse e-mail n\'est pas la même.', 'ADRESSE A RENTRER ICI'),
							],
							]));
		
		
		
		
		;
		
	}
}