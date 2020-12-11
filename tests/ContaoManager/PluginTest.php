<?php

declare(strict_types=1);

/*
 * FAQ Tags Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2020, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\TagsBundle\Test\ContaoManager;

use Codefog\FaqTagsBundle\CodefogFaqTagsBundle;
use Codefog\FaqTagsBundle\ContaoManager\Plugin;
use Codefog\TagsBundle\CodefogTagsBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\FaqBundle\ContaoFaqBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ContainerBuilder;
use Contao\ManagerPlugin\Config\ExtensionPluginInterface;
use PHPUnit\Framework\TestCase;

class PluginTest extends TestCase
{
    public function testInstantiation(): void
    {
        $plugin = new Plugin();

        $this->assertInstanceOf(BundlePluginInterface::class, $plugin);
        $this->assertInstanceOf(ExtensionPluginInterface::class, $plugin);
    }

    public function testGetBundles(): void
    {
        $plugin = new Plugin();
        $bundles = $plugin->getBundles($this->createMock(ParserInterface::class));

        /** @var BundleConfig $config */
        $config = $bundles[0];

        $this->assertCount(1, $bundles);
        $this->assertInstanceOf(BundleConfig::class, $config);
        $this->assertEquals(CodefogFaqTagsBundle::class, $config->getName());
        $this->assertContains(ContaoCoreBundle::class, $config->getLoadAfter());
        $this->assertContains(ContaoFaqBundle::class, $config->getLoadAfter());
        $this->assertContains(CodefogTagsBundle::class, $config->getLoadAfter());
    }

    public function testGetExtensionConfig(): void
    {
        $plugin = new Plugin();
        $config = $plugin->getExtensionConfig('codefog_tags', [], $this->createMock(ContainerBuilder::class));

        $this->assertEquals(
            [
                [
                    'managers' => [
                        'codefog_faq' => [
                            'source' => 'tl_faq.tags',
                        ],
                    ],
                ],
            ],
            $config
        );
    }
}
