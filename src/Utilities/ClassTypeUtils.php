<?php namespace PHPFHIR\Utilities;

use PHPFHIR\Enum\BaseObjectTypeEnum;
use PHPFHIR\Enum\ComplexClassTypesEnum;
use PHPFHIR\Enum\SimpleClassTypesEnum;

/**
 * Class ClassTypeUtils
 * @package PHPFHIR\Utilities
 */
abstract class ClassTypeUtils
{
    /**
     * @param string|\SimpleXMLElement $input
     * @return SimpleClassTypesEnum
     */
    public static function getSimpleClassType($input)
    {
        if ($input instanceof \SimpleXMLElement)
            $name = XMLUtils::getObjectNameFromElement($input);
        else
            $name = $input;

        if (is_string($name))
            return new SimpleClassTypesEnum(ltrim(strrchr($name, '-'), "-"));

        throw new \InvalidArgumentException('Unable to determine Simple Class Type for "'.(string)$input.'"');
    }

    /**
     * @param \SimpleXMLElement $sxe
     * @return null|ComplexClassTypesEnum
     */
    public static function getComplexClassType(\SimpleXMLElement $sxe)
    {
        $name = XMLUtils::getObjectNameFromElement($sxe);
        if (false !== strpos($name, '.'))
            return new ComplexClassTypesEnum(ComplexClassTypesEnum::COMPONENT);

        $baseName = XMLUtils::getBaseObjectName($sxe);
        if (null === $baseName)
            return null;

        $baseType = new BaseObjectTypeEnum($baseName);
        switch((string)$baseType)
        {
            case BaseObjectTypeEnum::BACKBONE_ELEMENT:
                return new ComplexClassTypesEnum(ComplexClassTypesEnum::RESOURCE);

            default:
                return new ComplexClassTypesEnum((string)$baseType);
        }
    }
}