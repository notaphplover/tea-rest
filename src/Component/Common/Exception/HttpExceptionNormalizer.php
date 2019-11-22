<?php

namespace App\Component\Common\Exception;

use App\Component\Serialization\Service\SerializationProvider;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class HttpExceptionNormalizer implements NormalizerInterface
{
    /**
     * @var SerializationProvider
     */
    protected $serializationProvider;

    /**
     * HttpExceptionNormalizer constructor.
     * @param SerializationProvider $serializationProvider
     */
    public function __construct(SerializationProvider $serializationProvider)
    {
        $this->serializationProvider = $serializationProvider;
    }

    /**
     * @param BaseHttpException $object
     * @param null $format
     * @param array $context
     * @return array|\ArrayObject|bool|float|int|string|null
     */
    public function normalize($object, $format = null, array $context = array())
    {
        $this->serializationProvider->getSerializer()->serialize(
            [
                'code' => $object->getCode(),
                'message' => $object->getMessage()
            ],
            'json'
        );
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof BaseHttpException;
    }
}
