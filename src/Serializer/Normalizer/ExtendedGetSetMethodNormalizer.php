<?php
/**
 * 23.01.2020.
 */

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class ExtendedGetSetMethodNormalizer extends GetSetMethodNormalizer
{
    private static $setterAccessibleCache = [];

    /**
     * {@inheritdoc}
     */
    protected function setAttributeValue(object $object, string $attribute, $value, string $format = null, array $context = [])
    {
        $setter = 'set'.ucfirst($attribute);
        $key = \get_class($object).':'.$setter;
        if (!isset(self::$setterAccessibleCache[$key])) {
            self::$setterAccessibleCache[$key] = \is_callable([
                    $object,
                    $setter,
                ]) && !(new \ReflectionMethod($object, $setter))->isStatic();
        }

        if (self::$setterAccessibleCache[$key]) {
            /** @var \ReflectionParameter $param */
            foreach ((new \ReflectionMethod($object, $setter))->getParameters() as $param) {
                /** @var \ReflectionNamedType $type */
                $type = $param->getType();
                if (null !== $type) {
                    //set null if empty
                    if (empty($value) && $type->allowsNull() && 'string' !== $type->getName()) {
                        $value = null;
                    }
                    if (!empty($value) && 'float' === $type->getName()) {
                        $value = \str_replace(',', '.', $value);
                    }
                }
            }
        }
        parent::setAttributeValue($object, $attribute, $value, $format, $context);
    }
}
