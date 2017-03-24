<?php
namespace FormBuilder;

use OCFram\BackController;
use Entity\Member;
use OCFram\Entity;
use \OCFram\FormBuilder;
use OCFram\PasswordField;
use \OCFram\StringField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\EqualValidator;
use OCFram\UniqueValidator;
use OCFram\EmailValidator;


class MemberFormBuilder extends FormBuilder {
	protected $Controller;
	protected $Member;
	
	public function __construct( Entity $Entity, BackController $Controller, Member $Member = null ) {
		parent::__construct( $Entity );
		$this->Controller = $Controller;
		$this->Member     = $Member;
	}
	
	public function build() {
		$this->form->add( new StringField( [
			'label'      => 'Nom d\'utilisateur',
			'name'       => 'user',
			'maxLength'  => 20,
			'validators' => [
				new MaxLengthValidator( 'L\'username choisi est trop long (20 caractères maximum)', 20 ),
				new NotNullValidator( 'Il vous faut un nom d\'utilisateur !' ),
				new UniqueValidator( 'Ce pseudo est déjà utilisé par un autre membre !', $this->Controller->Managers()->getManagerOf( 'Member' ), 'existMemberUsingPseudo' ),
			],
		] ) )->add( $passwordField = new PasswordField( [
				'label'      => 'Mot de passe',
				'name'       => 'password',
				'maxLength'  => 20,
				'validators' => [
					new MaxLengthValidator( 'Le mot de passe choisi est trop long (20 caractères maximum)', 20 ),
					new NotNullValidator( 'Il vous faut un mot de passe !' ),
				],
			] ) )->add( new PasswordField( [
				'label'      => 'Confirmation du mot de passe',
				'name'       => 'passwordConfirmation',
				'maxLength'  => 20,
				'validators' => [
					new MaxLengthValidator( 'Le mot de passe choisi est trop long (20 caractères maximum)', 20 ),
					new NotNullValidator( 'Veuillez confirmer votre mot de passe.' ),
					new EqualValidator( 'Le mot de passe n\'est pas le même.', $passwordField ),
				],
			] ) )->add( $emailField = new StringField( [
				'label'      => 'Adresse e-mail',
				'name'       => 'email',
				'maxLength'  => 40,
				'validators' => [
					new MaxLengthValidator( 'L\'adresse e-mail choisie est trop longue (40 caractères maximum)', 40 ),
					new NotNullValidator( 'Veuillez entrer votre adresse e-mail.' ),
					new EmailValidator( 'L\'email saisi n\'est pas valide' ),
					new UniqueValidator( 'Cet email est déjà utilisé par un autre membre !', $this->Controller->Managers()->getManagerOf( 'Member' ), 'existMemberUsingEmail' ),
				],
			] ) )->add( new StringField( [
				'label'      => 'Confirmation de l\'adresse e-mail',
				'name'       => 'emailConfirmation',
				'maxLength'  => 40,
				'validators' => [
					new MaxLengthValidator( 'L\'adresse e-mail choisie est trop longue (40 caractères maximum)', 40 ),
					new NotNullValidator( 'Veuillez confirmer votre adresse e-mail.' ),
					new EqualValidator( 'L\'adresse e-mail n\'est pas la même.', $emailField ),
				],
			] ) );
		
		if ( 2 == $this->Member['status'] ) {
			$this->form->add( new StringField( [
				'label'      => 'Statut (1 pour user classique, 2 pour admin)',
				'name'       => 'status',
				'maxLength'  => 50,
				'validators' => [
					new NotNullValidator( 'Merci de spécifier le statut du membre' ),
				],
			] ) );
		};
	}
}