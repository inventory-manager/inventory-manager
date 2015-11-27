<?php

namespace AppBundle\Converter;

use AppBundle\Models\TestModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TestModelConverter
 * @package AppBundle\Converter
 */
class TestModelConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        $decoded = json_decode($request->getContent(), true);

        if (!isset($decoded['id']) || !isset($decoded['name'])) {
            throw new \InvalidArgumentException('TestModel konnte nicht erstellt werden, falsche Parameter', 400);
        } else {
            $model = new TestModel();
            $model->setModelId($decoded['id'] ?: null);
            $model->setModelName($decoded['name'] ?: null);

            $request->attributes->set($configuration->getName(), $model);

            return true;
        }
    }

    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === 'AppBundle\Models\TestModel';
    }
}
