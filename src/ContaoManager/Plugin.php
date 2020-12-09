<?php

declare(strict_types=1);

/*
 * FAQ Tags Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2020, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\FaqTagsBundle\ContaoManager;

use Codefog\FaqTagsBundle\CodefogFaqTagsBundle;
use Codefog\TagsBundle\CodefogTagsBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ContainerBuilder;
use Contao\ManagerPlugin\Config\ExtensionPluginInterface;

class Plugin implements BundlePluginInterface, ExtensionPluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(CodefogFaqTagsBundle::class)->setLoadAfter([ContaoCoreBundle::class, CodefogTagsBundle::class]),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getExtensionConfig($extensionName, array $extensionConfigs, ContainerBuilder $container)
    {
        if ('codefog_tags' === $extensionName && !isset($extensionConfigs[0]['managers']['codefog_faq'])) {
            $extensionConfigs[0]['managers']['codefog_faq'] = [
                'source' => 'tl_faq.tags',
            ];
        }

        return $extensionConfigs;
    }
}
