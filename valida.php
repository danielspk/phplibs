<?php
namespace DMS\Libs;

/**
 * Clase de validaciones estandar
 *
 * @package LIBS
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/LIBS
 * @license https://github.com/danielspk/LIBS/blob/master/LICENSE MIT License
 * @version 1.0.0
 */
class Valida {

	public function nombreApellido($pApeNom) {
		return (
			strlen(trim($pApeNom)) >= 4 && 
			filter_var(
				$pApeNom, FILTER_VALIDATE_REGEXP, array(
					"options" => array(
						"regexp" => "/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\-\_\']*$/"
					)
				)
			)
		);
	}
	
	public function contrasenia($pClave, $pMin = 6, $pMax = 15)
	{
		$largo = strlen($pClave);
		
		return (
			$largo >= $pMin && 
			$largo <= $pMax &&
			filter_var(
				$pClave, FILTER_VALIDATE_REGEXP, array(
					"options" => array(
						"regexp" => "/^[a-zA-Z0-9\-\_\+\*]*$/"
					)
				)
			)
		);
	}

	public function email($pEmail)
	{
		return filter_var($pEmail, FILTER_VALIDATE_EMAIL);
	}
	
	public function entero($pNumero)
	{
		return (
			filter_var(
				$pNumero, FILTER_VALIDATE_REGEXP, array(
					"options" => array(
						"regexp" => "/^[0-9]*$/"
					)
				)
			)
		);
	}
	
	public function decimal($pNumero)
	{
		return filter_var($pNumero, FILTER_VALIDATE_FLOAT);
	}
	
	// formato de entrada AAAA-MM-DD
	public function fecha($pFecha) {
		
		if (strlen($pFecha)!= 10)
			return false;
		
		$dia = substr($pFecha, 8, 2);
		$mes = substr($pFecha, 5, 2);
		$anio = substr($pFecha, 0, 4);
		
		return ($this->entero($dia) && $this->entero($mes) && $this->entero($anio) && checkdate($mes, $dia, $anio));
	}
	
	public function esAjax()
	{
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}
	
}
