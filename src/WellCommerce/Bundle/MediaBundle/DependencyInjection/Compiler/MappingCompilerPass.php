<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 * 
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 * 
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\MediaBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\Compiler\AbstractMappingCompilerPass;
use WellCommerce\Bundle\MediaBundle\DependencyInjection\WellCommerceMediaExtension;

/**
 * Class MappingCompilerPass
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class MappingCompilerPass extends AbstractMappingCompilerPass
{
    protected function getExtensionConfiguration(ContainerBuilder $container)
    {
        return $container->getParameter(WellCommerceMediaExtension::EXTENSION_NAME);
    }
}
