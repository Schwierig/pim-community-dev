<?php

namespace Pim\Bundle\VersioningBundle\Normalizer\Flat;

use Pim\Component\Catalog\Model\AttributeInterface;
use Pim\Component\Catalog\Model\AttributeOptionInterface;
use Pim\Component\Catalog\Normalizer\Standard\AttributeOptionNormalizer as StandardNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalize an attribute option
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AttributeOptionNormalizer implements NormalizerInterface
{
    /** @var string[] */
    protected $supportedFormats = ['flat'];

    /** @var StandardNormalizer */
    protected $standardNormalizer;

    /** @var TranslationNormalizer  */
    protected $translationNormalizer;

    /**
     * AttributeNormalizer constructor.
     *
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
     *
     * @param AttributeOptionInterface $object
     *
     * @return array
     */
    public function normalize($object, $format = null, array $context = [])
    {
        if (!$this->standardNormalizer->supportsNormalization($object, 'standard')) {
            return null;
        }

        $standardAttributeOption = $this->standardNormalizer->normalize($object, 'standard', $context);
        $flatAttributeOption = $standardAttributeOption;

        unset($flatAttributeOption['labels']);
        if ($this->translationNormalizer->supportsNormalization($standardAttributeOption['labels'], 'flat')) {
            $flatAttributeOption += $this->translationNormalizer->normalize(
                $standardAttributeOption['labels'],
                'flat',
                $context
            );
        }

        return $flatAttributeOption;
    }


    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof AttributeOptionInterface && in_array($format, $this->supportedFormats);
    }
}
