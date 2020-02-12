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
        $setter = 'set' . ucfirst($attribute);
        $key = \get_class($object) . ':' . $setter;
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
                if ($type !== null) {
                    //set null if empty
                    if (empty($value) && $type->allowsNull() && $type->getName() !== 'string') {
                        $value = null;
                    }
                    if (!empty($value) && $type->getName() === 'float') {
                        $value = \str_replace(',', '.', $value);
                    }
                }
            }
        }
        parent::setAttributeValue($object, $attribute, $value, $format, $context);
    }
}
