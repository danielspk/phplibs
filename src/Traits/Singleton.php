<?php
namespace DMS\PHPLibs\Traits;

/**
 * Trait para implementar clases singletons
 *
 * Extraido de http://php.net/manual/es/language.oop5.traits.php
 *
 * @package LIBS
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/LIBS
 * @license https://github.com/danielspk/LIBS/blob/master/LICENSE MIT License
 * @version 0.8.2
 */
trait Singleton
{
	
	public static function getInstance() {
        static $_instance = NULL;
        $class = __CLASS__;
        return $_instance ?: $_instance = new $class;
    }
    
    public function __clone() {
        trigger_error('Cloning ' . __CLASS__ . ' is not allowed.', E_USER_ERROR);
    }
    
    public function __wakeup() {
        trigger_error('Unserializing ' . __CLASS__ . ' is not allowed.', E_USER_ERROR);
    }
	
}
