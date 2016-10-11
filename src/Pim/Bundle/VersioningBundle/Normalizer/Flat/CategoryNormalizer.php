<?php

namespace Pim\Bundle\VersioningBundle\Normalizer\Flat;

use Pim\Component\Catalog\Model\CategoryInterface;
use Pim\Component\Catalog\Normalizer\Standard\CategoryNormalizer as StandardNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * A normalizer to transform a category entity into a flat array
 *
 * @author    Filips Alpe <filips@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CategoryNormalizer implements NormalizerInterface
{
    /**  @var string[] */
    protected $supportedFormats = ['flat'];

    /** @var TranslationNormalizer */
    protected $translationNormalizer;

    /** @var CategoryNormalizer */
    protected $standardNormalizer;

    /**
     * @param StandardNormalizer    $standardNormalizer
     * @param TranslationNormalizer $translationNormalizer
     */
    public function __construct(
        StandardNormalizer $standardNormalizer,
        TranslationNormalizer $translationNormalizer
    ) {
        $this->standardNormalizer = $standardNormalizer;
        $this->translationNormalizer = $translationNormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!$this->standardNormalizer->supportsNormalization($object, 'standard')) {
            return null;
        }

        $standardCategory = $this->standardNormalizer->normalize($object, 'standard', $context);
        $flatCategory = $standardCategory;

        unset($flatCategory['labels']);
        if ($this->translationNormalizer->supportsNormalization($standardCategory['labels'], 'flat')) {
            $flatCategory += $this->translationNormalizer->normalize($standardCategory['labels'], 'flat', $context);
        }

        return $flatCategory;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof CategoryInterface && in_array($format, $this->supportedFormats);
    }
}
