<?php
namespace OCFram;

class PasswordField extends StringField {
	public function buildWidget() {
		$widget = '';
		
		if ( !empty( $this->errorMessage ) ) {
			$widget .= $this->errorMessage . '<br />';
		}
		
		$widget .= '<label>' . $this->label . '</label><input type="password" name="' . $this->name . '"';
		
		
		if ( !empty( $this->maxLength ) ) {
			$widget .= ' maxlength="' . $this->maxLength . '"';
		}
		
		return $widget . ' />';
	}
}