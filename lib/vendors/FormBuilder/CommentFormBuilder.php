<?php
namespace FormBuilder;

use Entity\Member;
use OCFram\Application;
use OCFram\Entity;
use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class CommentFormBuilder extends FormBuilder {
	protected $Member;
	
	public function __construct( Entity $Entity, Member $Member = null ) {
		parent::__construct( $Entity );
		$this->Member = $Member;
	}
	
	public function build() {
		if ( null === $this->Member ) {
			$this->form->add( new StringField( [
				'label'      => 'Auteur',
				'name'       => 'auteur',
				'maxLength'  => 50,
				'validators' => [
					new MaxLengthValidator( 'L\'auteur spécifié est trop long (50 caractères maximum)', 50 ),
					new NotNullValidator( 'Merci de spécifier l\'auteur du commentaire' ),
				],
			] ) );
		}
		
		$this->form->add( new TextField( [
			'label'      => 'Contenu',
			'name'       => 'contenu',
			'rows'       => 7,
			'cols'       => 50,
			'validators' => [
				new NotNullValidator( 'Merci de spécifier votre commentaire' ),
			],
		] ) );
		
		return $this;
	}
}