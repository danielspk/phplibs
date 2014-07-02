<?php
namespace DMS\Libs;

/**
 * Clase de conexión a base de datos
 *
 * @package LIBS
 * @author Daniel M. Spiridione <info@daniel-spiridione.com.ar>
 * @link https://github.com/danielspk/LIBS
 * @license https://github.com/danielspk/LIBS/blob/master/LICENSE MIT License
 * @version 1.0.0
 */
class DataBase extends \PDO
{

	/**
	 * Instancia de la clase (patrón Singleton)
	 * @var \DMS\Libs\DataBase 
	 */
	private static $_instancia = null;

	/**
	 * Constructor de la clase
	 * @param array $pConfig Array de configuración
	 * @throws \ErrorException
	 */
	public function __construct($pConfig)
	{

		switch($pConfig['motor']){
			case 'SQLSRV':
				parent::__construct(
					'sqlsrv:Server=' . $pConfig['host'] .
					';Database=' . $pConfig['base'],
					$pConfig['user'],
					$pConfig['pass']
				);
				break;
			case 'MSSQL':
				parent::__construct(
					'odbc:Driver={SQL Server};Server=' . $pConfig['host'] .
					';Database=' . $pConfig['base'] .
					';Uid=' . $pConfig['user'] .
					';Pwd=' . $pConfig['pass']
				);
				break;
			case 'ACCESS':
				parent::__construct(
					'Driver={Microsoft Access Driver (*.mdb)};Dbq=' .
					$pConfig['path'],
					$pConfig['user'],
					$pConfig['pass']
				);
				break;
			case 'MYSQL':
				parent::__construct(
					'mysql:host=' . $pConfig['host'] .
					';dbname=' . $pConfig['base'],
					$pConfig['user'],
					$pConfig['pass'],
					array(
						\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $pConfig['collation']
					)
				);
				break;
			case 'PGSQL':
				parent::__construct(
					'pgsql:host=' . $pConfig['host'] .
					';dbname=' . $pConfig['base'] . 
					';user=' . $pConfig['user'] . 
					';password=' . $pConfig['pass']
				);
				break;
			default:
				throw new \ErrorException('Motor de base de datos no soportado', E_USER_ERROR);
		}
			
    }
	
	/**
	 * Método que crea una instancia o devuelve la actual (patrón Singleton)
	 * @param array $pConfig Array de configuración [opcional si ya existe una instancia]
	 * @return type
	 */
	public static function conectar($pConfig = null)
	{
		
        if (self::$_instancia === null) {
			self::$_instancia = new self($pConfig);
			self::$_instancia->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$_instancia;
        
    }
	
	/**
	 * Método que elimina la instancia actual
	 */
	public static function desconectar()
	{
		self::$_instancia = null;
	}
	
}
