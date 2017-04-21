<?php
/**
 * Created by PhpStorm.
 * @author tomas
 * @package mkn
 * Date: 2017-04-20
 * Time: 11:17
 */

namespace MKN\Provider;


use MKN\Provider\Entity\VETA;

class Provider
{

    const
        COLUMN_NAME = 'name',
        COLUMN_CODE = 'code',
        COLUMN_PARENT = 'parent';

    private $db;
    private $db_file = __DIR__.'/../mkn.db';

    /**
     * $_stm
     * @var \PDOStatement
     */
    private $_stm;


    public function __construct()
    {
        $dsn = 'sqlite:'.realpath($this->db_file);
        $this->db = new \PDO($dsn);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE,\PDO::ERRMODE_EXCEPTION);
    }

    public function getOneByCode($code): VETA
    {
        $stm = $this->db->prepare('SELECT * FROM mkn WHERE '.self::COLUMN_NAME.' LIKE :name;');
        $stm->bindValue(':code',$this->sanitize($code));
        return $stm->fetchObject(VETA::class);
    }

    public function getOneByName($name) : VETA
    {
        $stm = $this->db->prepare( 'SELECT * FROM mkn WHERE '.self::COLUMN_CODE . 'LIKE :code;');
        $stm->bindValue(':name',$this->sanitize($name));
        return $stm->fetchObject(VETA::class);
    }

    /**
     * filterByCode
     * @param $code
     * @param bool $leading true for leading wildcard [%]
     * @param bool $tailing true for tailing wildcard [%]
     * @return array
     */
    public function filterByCode($code, $leading = true, $tailing = true) : array
    {
        $stm = $this->db->prepare( 'SELECT * FROM mkn WHERE '.self::COLUMN_CODE . 'LIKE :code;');
        $stm->bindValue(':code',($leading?'%':'').$this->sanitize($code).($tailing?'%':''));
        return $stm->fetchAll(\PDO::FETCH_CLASS,VETA::class);
    }

    /**
     * filterByName
     * @param $name
     * @param bool $leading true for leading wildcard [%]
     * @param bool $tailing true for tailing wildcard [%]
     * @return array
     */
    public function filterByName( $name, $leading = true, $tailing = true) : array
    {
        $stm = $this->db->prepare('SELECT * FROM mkn WHERE '.self::COLUMN_NAME.' LIKE :name;');
        $stm->bindValue(':name',($leading?'%':'').$this->sanitize($name).($tailing?'%':''));
        return $stm->fetchAll(\PDO::FETCH_CLASS,VETA::class);
    }

    private function sanitize($str): string {
        return str_replace(array('%','*',';'),'',$str);
    }

    public final function insert($code, $name, $parent = null){
        if($this->_stm === null){
            $query = 'INSERT INTO `mkn` (' . self::COLUMN_CODE . ','. self::COLUMN_NAME . ',' . self::COLUMN_PARENT . ') VALUES (?,?,?);';
            $this->_stm = $this->db->prepare($query);
        }
        $this->_stm->execute(array(
            $code,
            $name,
            $parent
        ));
    }
}