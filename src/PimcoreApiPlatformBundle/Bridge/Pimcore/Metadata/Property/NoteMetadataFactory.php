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

namespace Wvision\Bundle\PimcoreApiPlatformBundle\Bridge\Pimcore\Metadata\Property;

use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\PropertyMetadata;
use ApiPlatform\Core\Metadata\Property\SubresourceMetadata;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Image;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\Element\AbstractElement;
use Pimcore\Model\Element\ElementInterface;
use Pimcore\Model\Element\Note;
use Pimcore\Model\Property;
use Symfony\Component\PropertyInfo\Type;
use Wvision\Bundle\PimcoreApiPlatformBundle\Bridge\Pimcore\UnionType;

final class NoteMetadataFactory implements PropertyMetadataFactoryInterface
{
    private $decorated;

    public function __construct(PropertyMetadataFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $resourceClass, string $property, array $options = []): PropertyMetadata
    {
        $propertyMetadata = $this->decorated->create($resourceClass, $property, $options);

        if (!$resourceClass === Note::class) {
            return $propertyMetadata;
        }

        switch ($property) {
            case 'id':
                $propertyMetadata = $propertyMetadata->withType(
                    new Type(Type::BUILTIN_TYPE_STRING, false)
                );
                $propertyMetadata = $propertyMetadata->withIdentifier(true);
                break;
            case 'note':
                $propertyMetadata = $propertyMetadata->withType(
                    new Type(Type::BUILTIN_TYPE_STRING, false)
                );
                break;
            case 'cid':
                $propertyMetadata = $propertyMetadata->withType(
                    new Type(Type::BUILTIN_TYPE_INT, false)
                );
                break;
            case 'ctype':
                $propertyMetadata = $propertyMetadata->withType(
                    new Type(Type::BUILTIN_TYPE_STRING, false)
                );
                break;
            case 'date':
                $propertyMetadata = $propertyMetadata->withType(
                    new Type(Type::BUILTIN_TYPE_INT, false)
                );
                break;
            case 'title':
                $propertyMetadata = $propertyMetadata->withType(
                    new Type(Type::BUILTIN_TYPE_STRING, false)
                );
                break;
            case 'description':
                $propertyMetadata = $propertyMetadata->withType(
                    new Type(Type::BUILTIN_TYPE_STRING, false)
                );
                break;
        }

        return $propertyMetadata;
    }
}
