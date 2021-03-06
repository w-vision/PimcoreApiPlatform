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

use ApiPlatform\Core\Metadata\Property\Factory\PropertyNameCollectionFactoryInterface;
use ApiPlatform\Core\Metadata\Property\PropertyNameCollection;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\Concrete;

final class AssetPropertyNameCollectionFactory implements PropertyNameCollectionFactoryInterface
{
    private $decorated;

    public function __construct(PropertyNameCollectionFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function create(string $resourceClass, array $options = []): PropertyNameCollection
    {
        $collection = $this->decorated->create($resourceClass, $options);

        if ($resourceClass !== Asset::class && !is_subclass_of($resourceClass, Asset::class)) {
            return $collection;
        }

        $properties = [];

        if (null !== $collection) {
            foreach ($collection as $propertyName) {
                $properties[$propertyName] = $propertyName;
            }
        }

        unset($properties['data']);
        unset($properties['temporaryFile']);

        $properties['fullPath'] = true;
        $properties['properties'] = true;

        if ($resourceClass !== Asset\Folder::class) {
            $properties['data'] = true;
        }

        if ($resourceClass === Asset\Image::class) {
            $properties['thumbnail'] = true;
        }

        return new PropertyNameCollection(array_keys($properties));
    }
}
