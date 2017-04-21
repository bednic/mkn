<?php
/**
 * Created by PhpStorm.
 * @author tomas
 * @package mkn
 * Date: 2017-04-20
 * Time: 11:17
 */

namespace MKN\Provider\Entity;



class VETA implements \JsonSerializable
{
    /**
     * $code
     * @var string
     */
    public $code;
    /**
     * $name
     * @var string
     */
    public $name;

    /**
     * $parent
     * @var string
     */
    public $parent = null;

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return $this->parent;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize() : array
    {
        return [
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'parent' => $this->getParent()
        ];
    }
}