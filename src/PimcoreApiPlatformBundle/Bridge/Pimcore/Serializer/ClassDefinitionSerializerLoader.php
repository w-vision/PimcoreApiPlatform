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

namespace Wvision\Bundle\PimcoreApiPlatformBundle\Bridge\Pimcore\Serializer;

use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\Concrete;
use Symfony\Component\Serializer\Mapping\AttributeMetadata;
use Symfony\Component\Serializer\Mapping\ClassMetadataInterface;
use Symfony\Component\Serializer\Mapping\Loader\LoaderInterface;

class ClassDefinitionSerializerLoader extends AbstractPimcoreDefinitionSerializerLoader
{
    public function loadClassMetadata(ClassMetadataInterface $classMetadata)
    {
        $class = $classMetadata->getName();

        if (!is_subclass_of($class, Concrete::class)) {
            return $classMetadata;
        }

        // If not directly a Pimcore DataObject Class (eg. parent classAdditionalData
        if (false === strpos('Pimcore\\Model\\DataObject', $class)) {
            return $classMetadata;
        }

        $class = ClassDefinition::getById($class::classId());

        if (!$class) {
            return $classMetadata;
        }

        $localizedFieldDefs = [];
        $localizedFields = $class->getFieldDefinition('localizedfields');

        if ($localizedFields instanceof ClassDefinition\Data\Localizedfields) {
            $localizedFieldDefs = $localizedFields->getFieldDefinitions();
        }

        foreach ([$class->getFieldDefinitions(), $localizedFieldDefs] as $fieldDefinitions) {
            foreach ($fieldDefinitions as $fieldDefinition) {
                if ($fieldDefinition instanceof ClassDefinition\Data\Localizedfields) {
                    foreach ($fieldDefinition->getFieldDefinitions() as $subDefinition) {
                        $this->addMetadata($classMetadata, $class->getName(), $subDefinition->getName());
                    }
                }

                $this->addMetadata($classMetadata, $class->getName(), $fieldDefinition->getName());
            }
        }

        return $classMetadata;
    }
}
