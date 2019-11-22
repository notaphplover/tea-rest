<?php


namespace App\Component\Serialization\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializationProvider
{
    /**
     * @var Serializer
     */
    protected $serializer;

    public function __construct(ClassMetadataFactoryInterface $classMetadataFactory)
    {
        $this->serializer = new Serializer([new DateTimeNormalizer(), new ObjectNormalizer($classMetadataFactory)], [new JsonEncoder()]);
    }

    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }
}
