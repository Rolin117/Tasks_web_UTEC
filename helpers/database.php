<?php
require_once('conexion.php');

/*
 *   Clase para realizar las operaciones en la base de datos.
 */
class Database
{
    private static $connection = null;
    private static $statement = null;
    private static $error = null;

    /*
     *   Método para obtener la conexión PDO creada en conexion.php.
     */
    private static function getConnection()
    {
        if (self::$connection === null) {
            if (!isset($GLOBALS['conexion']) || !($GLOBALS['conexion'] instanceof PDO)) {
                throw new RuntimeException('No se encontró la conexión $conexion. Revisa helpers/conexion.php');
            }
            self::$connection = $GLOBALS['conexion'];
        }
        return self::$connection;
    }

    public static function executeRow($query, $values = null)
    {
        try {
            // Se prepara la sentencia SQL con la conexión compartida.
            self::$statement = self::getConnection()->prepare($query);
            // Se ejecuta la sentencia preparada y se retorna el resultado.
            return self::$statement->execute($values);
        } catch (PDOException $error) {
            // PDO retorna en getCode() el SQLSTATE (por ejemplo 'HY000') y el código propio
            // de MySQL/MariaDB (por ejemplo 1045) viene en errorInfo[1].
            $driverCode = isset($error->errorInfo[1]) ? (int) $error->errorInfo[1] : 0;
            self::setException($driverCode, $error->getMessage());
            return false;
        }
    }

    /*
     *   Método para obtener el valor de la llave primaria del último registro insertado.
     *   Retorno: numérico entero (último valor de la llave primaria o 0 en caso de error).
     */
    public static function getLastRow($query, $values)
    {
        if (self::executeRow($query, $values)) {
            $id = self::$connection->lastInsertId();
        } else {
            $id = 0;
        }
        return $id;
    }

    /*
     *   Método para obtener un registro de una sentencia SQL tipo SELECT.
     *   Retorno: arreglo asociativo del registro o false en caso de error.
     */
    public static function getRow($query, $values = null)
    {
        if (self::executeRow($query, $values)) {
            return self::$statement->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /*
     *   Método para obtener todos los registros de una sentencia SQL tipo SELECT.
     *   Retorno: arreglo asociativo de los registros o false en caso de error.
     */
    public static function getRows($query, $values = null)
    {
        if (self::executeRow($query, $values)) {
            return self::$statement->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    /*
     *   Método para establecer un mensaje de error personalizado al ocurrir una excepción.
     *   Parámetros: $code (código de error de MySQL/MariaDB) y $message (mensaje original del error).
     */
    private static function setException($code, $message)
    {
        // El mensaje original se guarda en el log del servidor para poder depurar.
        error_log('Database error [' . $code . ']: ' . $message);
        switch ($code) {
            case 2002:
                self::$error = 'Servidor desconocido';
                break;
            case 1049:
                self::$error = 'Base de datos desconocida';
                break;
            case 1045:
                self::$error = 'Acceso denegado';
                break;
            case 1146:
                self::$error = 'Tabla no encontrada';
                break;
            case 1054:
                self::$error = 'Columna no encontrada';
                break;
            case 1062:
            case 1451:
            case 1452:
                self::$error = 'Violación de restricción de integridad';
                break;
            default:
                self::$error = 'Ocurrió un problema en la base de datos';
        }
    }

    /*
     *   Método para obtener un error personalizado cuando ocurre una excepción.
     */
    public static function getException()
    {
        return self::$error;
    }
}