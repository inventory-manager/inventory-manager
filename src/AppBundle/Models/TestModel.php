<?php

namespace AppBundle\Models;

/**
 * Class TestModel
 * @package AppBundle\Models
 */
class TestModel implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $modelName;
    /**
     * @var int
     */
    protected $modelId;

    /**
     * TestModel constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @param string $modelName
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
    }

    /**
     * @return int
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @param int $modelId
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->modelId,
            'name' => $this->modelName
        ];
    }
}
