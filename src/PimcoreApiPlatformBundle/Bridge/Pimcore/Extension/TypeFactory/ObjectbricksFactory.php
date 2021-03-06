<?php
/**
 * Pimcore Api Platform Bundle
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2016-2019 w-vision AG (https://www.w-vision.ch)
 * @license    https://github.com/w-vision/DataDefinitions/blob/master/gpl-3.0.txt GNU General Public License version 3 (GPLv3)
 */

namespace Wvision\Bundle\PimcoreApiPlatformBundle\Bridge\Pimcore\Extension\TypeFactory;

use ApiPlatform\Core\Metadata\Property\PropertyMetadata;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\ClassDefinition\Data;
use Symfony\Component\PropertyInfo\Type;
use Wvision\Bundle\PimcoreApiPlatformBundle\Bridge\Pimcore\Extension\DataObjectFieldTypeMetadataFactoryInterface;

class ObjectbricksFactory implements DataObjectFieldTypeMetadataFactoryInterface
{
    public function supports($classDefinition, Data $fieldDefinition): bool
    {
        if ($classDefinition instanceof ClassDefinition && $fieldDefinition instanceof Data\Objectbricks) {
            $class = sprintf('Pimcore\Model\DataObject\%s\%s',
                ucfirst($classDefinition->getName()),
                ucfirst($fieldDefinition->getName())
            );

            return class_exists($class);
        }

        return false;
    }

    public function create(
        $classDefinition,
        Data $fieldDefinition,
        PropertyMetadata $propertyMetadata
    ): PropertyMetadata {
        $containerType = new Type(
            Type::BUILTIN_TYPE_OBJECT,
            true,
            sprintf('Pimcore\Model\DataObject\%s\%s',
                ucfirst($classDefinition->getName()),
                ucfirst($fieldDefinition->getName())
            )
        );

        $propertyMetadata = $propertyMetadata->withType($containerType);

        return $propertyMetadata;
    }
}
