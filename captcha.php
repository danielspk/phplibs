<?php
namespace DMS\Libs;

/**
 * Clase de generación y validación de captchas
 *
 * @package LIBS
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/LIBS
 * @license https://github.com/danielspk/LIBS/blob/master/LICENSE MIT License
 * @version 1.0.0
 */
class Captcha {

	// propiedades públicas que se pueden editar desde la configuración o desde 
	// la instancia de la clase para adaptar la imagen Captcha
	// (por simplicidad de la clase no se ofrecen setters y getters)
	
	/**
	 * Alto del Captcha
	 * @var int 
	 */
	public $alto = 62;
	
	/**
	 * Ancho del Captcha
	 * @var int 
	 */
	public $ancho = 180;
	
	/**
	 * Cantidad de letras del Captcha
	 * @var int 
	 */
	public $letras = 6;
	
	/**
	 * Tamaño de la fuente de las letras
	 * @var int 
	 */
	public $tamanioFuente = 23;
	
	/**
	 * Espacio entre letras
	 * @var int
	 */
	public $espacioLetras = 3;
	
	/**
	 * Graduación de esfumado de letras
	 * 1: muy esfumado 50: sin esfumado
	 * @var int
	 */
	public $esfumado = 24;
	
	/**
	 * Cantidad de líneas que atraviesan el Captcha
	 * @var int
	 */
	public $lineas = 2;
	
	/**
	 * Cantidad de puntos a dibujar en el fondo del Captcha
	 * @var int
	 */
	public $puntos = 500;
	
	/**
	 * Color hexadecimal del fondo del Captcha
	 * @var string
	 */
	public $colorFondo = '#FFFFFF';
	
	/**
	 * Arreglo de colores hexadecimales para las letras
	 * @var array
	 */
	public $coloresLetras = array( // rojo, verde, azul, violeta, naranja
		'#DD0101',
		'#016401',
		'#014080',
		'#800180',
		'#F45001'
	);
	
	/**
	 * Imagen de fondo a utilizar en el Captcha
	 * Su uso reemplaza el color de fondo definido por código hexadecimal
	 * @var string
	 */
	public $fondo = '';

	// propiedades privadas que no pueden ser editardas con la clase declarada
	// @todo: pendiente de documentar
	private $imagen;
	private $colorFuente;
	private $colorImagen;
	private $colorImagenFuente;
	private $fuente;
	private $codigo;
	private $vocales = "aAeEiIoOuU";
	private $consonantes  = "bBcCdDfFgGhHjJkKlLmMnNpPqQrRsStTvVwWxXyYzZ";
	private $fuentes = array(
		'Almagro_Regular.ttf',
		'Kandide_Upper_Wide.ttf',
		'Lamebrain_BRK.ttf',
		'Nelson_Regular.ttf',
		'StayPuft.ttf'
	);
	
	/**
	 * Constructor de la clase
	 */
	public function __construct(){
		session_start();
	}
	
	/**
	 * Método que convierte colores hexadecimales en un array RGB
	 * @param string $hexadecimal Código hexadecimal
	 * @return array
	 */
	private function hexaToRGB($hexadecimal) {
		
		$color = str_replace('#', '', $hexadecimal);
		
		$colorRGB = array(
			'rojo' => hexdec(substr($color, 0, 2)),
			'verde' => hexdec(substr($color, 2, 2)),
			'azul' => hexdec(substr($color, 4, 2))
		);
		
		return $colorRGB;
		
	}
	
	/**
	 * Método que valida si el código ingresado es correcto
	 * @param string $codigo Código a validar
	 * @return boolean
	 */
	public function validarCaptcha($codigo){
	
		if( strtolower($codigo) ===  $_SESSION['codCaptchaDMS'] )
			return true;
		else
			return false;
			
	}
	
	/**
	 * Método que crea la imagen del Captcha
	 */
	public function generarImagen(){
		
		// se crear la imagen vacia
		$this->imagen = imagecreatetruecolor($this->ancho, $this->alto);
			
		if ($this->fondo) {
		
			// se crea una imagen temporal en base a la imagen del fondo
			$this->imagenTmp = imagecreatefromjpeg($this->fondo);
			
			// se copia imagen temporal a la imagen vacia
			imagecopyresized($this->imagen, $this->imagenTmp, 0, 0, 0, 0, $this->ancho, $this->alto, imagesx($this->imagenTmp), imagesy($this->imagenTmp));
			
			// se elimina la imagen temporal
			imagedestroy($this->imagenTmp);
			
		} else {
		
			//se convierte el color de fondo de la imagen de hexadecimal a un array RGB
			$this->colorFondo = $this->hexaToRGB($this->colorFondo);
			
			// se especifíca el color de fondo de la imagen creada
			$this->colorImagen = imagecolorallocate($this->imagen,$this->colorFondo['rojo'],$this->colorFondo['verde'],$this->colorFondo['azul']);
			
			// se crea el rectángulo de la imagen con el color previamente definido
			imagefilledrectangle($this->imagen, 0, 0, $this->ancho, $this->alto, $this->colorImagen);
		
		}

		// se busca aleatoriamente un color para las letras entre el array de colores hexadecimales
		$this->colorFuente = $this->coloresLetras[ mt_rand(0, count($this->coloresLetras)-1) ];
		
		//se convierte el color de la fuente obtenido de hexadecimal a un array RGB
		$this->colorFuente = $this->hexaToRGB($this->colorFuente);
		
		// se especifíca el color de fondo para las letras
		$this->colorImagenFuente = imagecolorallocate($this->imagen, $this->colorFuente['rojo'], $this->colorFuente['verde'], $this->colorFuente['azul']);
		
		// se crea un texto aleatorio combinando una vocal y una consonante
		$flag = mt_rand(0, 1);
		
		for ($i = 0; $i < $this->letras; $i++) {
		
			if ($flag) {
				$this->codigo .= substr($this->consonantes, mt_rand(0, 41), 1);
			} else {
				$this->codigo .= substr($this->vocales, mt_rand(0, 9), 1);
			}
			
			($flag)? $flag = 0 : $flag = 1;
			
		}
		
		// se selecciona una fuente aleatoriamente
		$this->fuente = __DIR__ . '/resources/fonts/' . $this->fuentes[ mt_rand(0, count($this->fuentes) -1 ) ];
		
		// coordenadas de incicio del texto
		$x = $this->tamanioFuente; // aproximadamente el espacio de una letra
		$y = round( ( $this->alto / 2) + ($this->tamanioFuente) / 2 ); // se centra en el eje Y (horizontal)
		
		// Se genera e inserta letra por letra en la imagen...
		for ($i=0; $i < $this->letras; $i++) {
			
			// se recupera la letra actual
			$letra = substr($this->codigo, $i, 1);
			
			// desplazamiento en el eje Y
			$yDesplaz = $y + mt_rand(-$this->tamanioFuente/3, $this->tamanioFuente/3);
			
			// rango de rotación de las letras
			$rotacion = mt_rand(-25, 25);
			
			// se agrega la letras en la imagen según las coordenadas y la rotación
			$coordenadas = imagettftext($this->imagen, $this->tamanioFuente, $rotacion, $x, $yDesplaz, $this->colorImagenFuente, $this->fuente, $letra);
			
			// se actualiza la coordenada X para la próxima letra
			$x = $coordenadas[2] + $this->espacioLetras;
			
		}
		
		// se crean las líneas
		for ($i = 0; $i < $this->lineas; $i++) {
		
			$x1 = mt_rand(0, $this->ancho/5);
			$y1 = mt_rand(0, $this->alto)% $this->alto;
			$x2 = $this->ancho - mt_rand(0, $this->ancho /3);
			$y2 = mt_rand(0, $this->ancho)%$this->alto;
			
			// se define un ancho variable
			$ancho = mt_rand(1, 3);
			
			for ($j=0; $j < $ancho; $j++) {
				imageline($this->imagen, $x1, $y1 + $j, $x2, $y2 + $j, $this->colorImagenFuente);
			}

		}
		
		// se crea un mapa de puntos
		for ($i = 0; $i < $this->puntos; $i++) {
			imagesetpixel($this->imagen, mt_rand(0, $this->ancho)%$this->ancho, mt_rand(0, $this->alto)%$this->alto, $this->colorImagenFuente);
		}
		
		// se crea un esfumado
		imagefilter($this->imagen, IMG_FILTER_SMOOTH, $this->esfumado);
		
		// se muestra la imagen final del Captcha
		header("Content-type: image/png");
		imagepng($this->imagen);
		
		// se elimina la imágen creada
		imagedestroy($this->imagen);

		// se guarda el código Captcha generado en la variable de Session
		$_SESSION['codCaptchaDMS'] = strtolower($this->codigo);
		
	}

}
