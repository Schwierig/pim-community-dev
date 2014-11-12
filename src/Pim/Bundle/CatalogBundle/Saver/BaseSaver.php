<?php

namespace Pim\Bundle\CatalogBundle\Saver;

use Pim\Component\Resource\Model\SaverInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Util\ClassUtils;

/**
 * Base saver, declared as different services for different classes
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class BaseSaver implements SaverInterface
{
    /** @var ObjectManager */
    protected $objectManager;

    /** @var string */
    protected $savedClass;

    /**
     * @param ObjectManager $manager
     * @param string        $savedClass
     */
    public function __construct(ObjectManager $objectManager, $savedClass)
    {
        $this->objectManager = $objectManager;
        $this->savedClass    = $savedClass;
    }

    /**
     * {@inheritdoc}
     */
    public function save($object, array $options = [])
    {
        if (!$object instanceof $this->savedClass) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Expects a "%s", "%s" provided.',
                    $this->savedClass,
                    ClassUtils::getClass($object)
                )
            );
        }

        $options = array_merge(['flush' => true], $options);
        $this->objectManager->persist($object);
        if (true === $options['flush']) {
            $this->objectManager->flush();
        }
    }
}
