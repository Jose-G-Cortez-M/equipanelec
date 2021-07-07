<?php

namespace App\Serializer;

use App\Entity\Material;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class MaterialNormalizer implements ContextAwareNormalizerInterface
{

    private $normalizer;
    private $urlHelper;

    public function __construct(
        ObjectNormalizer $normalizer,
        UrlHelper $urlHelper
    ) {
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize($material, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($material, $format, $context);

        if (!empty($material->getImagen())) {
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $material->getImagen());
        }
    
        return $data;
    }

    public function supportsNormalization($data, $format = null, array $context = [])
    {
        return $data instanceof Material;
    }
}
