<?php
namespace DMS\Libs;

/**
 * Clase de hashing y encriptaciones
 *
 * @package LIBS
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/LIBS
 * @license https://github.com/danielspk/LIBS/blob/master/LICENSE MIT License
 * @version 1.0.0
 */
class Cripto {

	/**
	 * Método que encriptar un texto
	 * @param string $pTexto Texto a encriptar
	 * @param string $pClave Clave de encriptación
	 * @return string
	 */
	public function encriptar($pTexto, $pClave)
	{
		// se obtiene un iv
		$ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
		
		// se retorna un string que sea válido para incluir en una url,
		// para eso se convierten los caracteres + y / por - y _ 
		// y adicionalmente se eliminan los = del final de la función de encriptado
		return rtrim(strtr(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $pClave, $pTexto, MCRYPT_MODE_ECB, $iv)), '+/', '-_'), '=');
	}

	/**
	 * Método que desencriptar un texto
	 * @param string $pTexto Texto a desencriptar
	 * @param string $pClave Clave de desencriptación
	 * @return string
	 */
	public function desencriptar($pTexto, $pClave)
	{
		// se revierten las caracteres cambiados y se agregan los = eliminados
		$texto = base64_decode(str_pad(strtr($pTexto, '-_', '+/'), strlen($pTexto) % 4, '=', STR_PAD_RIGHT));
		
		// se obtiene un iv
		$ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
		
		// dado que mcrypt_decrypt incluye caracteres ocultos al final del string
		// se eliminan los mismos con la función trim
		return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $pClave, $texto, MCRYPT_MODE_ECB, $iv), "\0\4");
	}
	
	/**
	 * Método que crea un hash (alfanumerico) aleatorio de un largo definido
	 * @param string $pLargo Largo del texto a generar [opcional]
	 * @return string
	 */
	public function crearHash($pLargo = 15)
	{
		
		$letras = 'aAbBcCdDeEfFgGhHiIoOpPqQrRsStTuUvVwWxXyYzZ0123456789_-';
		$cantLetras = strlen($letras) - 1;
		$hash = '';
		
		for($i = 0; $i < $pLargo; $i++) {
			$hash .= $letras[mt_rand(0, $cantLetras)];
		}
		
		return $hash;
		
	}
	
}
