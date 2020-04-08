<?php

namespace App\Serializer\Normalizer;

use App\Entity\TaskList;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class TaskListNormalizer implements NormalizerInterface
{
    private $packages;
    private $objectNormalizer;

    public function __construct(ObjectNormalizer $objectNormalizer, Packages $packages)
    {
        $this->packages = $packages;
        $this->objectNormalizer = $objectNormalizer;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        $object->setBackgroundPath(
            $this->packages->getUrl($object->getBackgroundPath(), 'backgrounds')
        );
        $context['ignored_attributes'] = ['user'];

        $data = $this->objectNormalizer->normalize($object, $format, $context);

        return $data;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof TaskList;
    }
}