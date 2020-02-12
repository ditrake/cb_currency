<?php
/**
 * 12.02.2020.
 */

declare(strict_types=1);

namespace App\Serializer\NameConverter;

use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

class XmlAttributesConverter implements NameConverterInterface
{
    public const ATTRIBUTE_PREFIX = 'attr';
    public const NODE_VALUE_ATTRIBUTE_NAME = 'value';

    private string $attributePrefix;
    private string $nodeValueAttributeName;
    /**
     * @var NameConverterInterface
     */
    private NameConverterInterface $decorator;

    public function __construct(NameConverterInterface $decorator, string $attributePrefix = self::ATTRIBUTE_PREFIX, string $nodeValueAttributeName = self::NODE_VALUE_ATTRIBUTE_NAME)
    {
        $this->attributePrefix = $attributePrefix;
        $this->nodeValueAttributeName = $nodeValueAttributeName;
        $this->decorator = $decorator;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($propertyName): string
    {
        if (0 === strncmp($this->nodeValueAttributeName, $propertyName, \strlen($this->nodeValueAttributeName))) {
            $propertyName = '#';
        }

        if (0 === strncmp($this->attributePrefix, $propertyName, \strlen($this->attributePrefix))) {
            $propertyName = '@'.substr($propertyName, \strlen($this->attributePrefix));
        }

        return $this->decorator->normalize($propertyName);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($propertyName): string
    {
        $propertyName = (string) $propertyName;

        if (0 === strpos($propertyName, '#')) {
            $propertyName = $this->nodeValueAttributeName;
        }

        if (0 === strpos($propertyName, '@')) {
            $propertyName = $this->attributePrefix.substr($propertyName, 1);
        }

        return $this->decorator->denormalize($propertyName);
    }
}
