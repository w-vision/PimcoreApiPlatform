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
use Pimcore\Model\Asset;
use Symfony\Component\PropertyInfo\Type;

final class AssetPropertyMetadataFactory implements PropertyMetadataFactoryInterface
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

        if ($resourceClass !== Asset::class && !is_subclass_of($resourceClass, Asset::class)) {
            return $propertyMetadata;
        }

        if ($property === 'id') {
            $propertyMetadata = $propertyMetadata->withIdentifier(true);

            if (null !== $propertyMetadata->isWritable()) {
                return $propertyMetadata;
            }

            $propertyMetadata = $propertyMetadata->withWritable(false);
        }

        if (null === $propertyMetadata->isIdentifier()) {
            $propertyMetadata = $propertyMetadata->withIdentifier(false);
        }

        switch ($property) {
            case 'fullPath':
            case 'thumbnail':
            case 'data':
                $propertyMetadata = $propertyMetadata->withType(new Type(Type::BUILTIN_TYPE_STRING));
                break;
        }

        return $propertyMetadata;
    }
}
