<?php


namespace Codefog\FaqTagsBundle\Test\DependencyInjection;

use Codefog\FaqTagsBundle\DependencyInjection\CodefogFaqTagsExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CodefogFaqTagsExtensionTest extends TestCase
{
    public function testLoad()
    {
        $container = new ContainerBuilder();
        $extension = new CodefogFaqTagsExtension();

        $extension->load([], $container);

        $this->assertTrue($container->hasDefinition('codefog_faq_tags.faq_manager'));
        $this->assertTrue($container->hasDefinition('codefog_faq_tags.faq_tag_list_module'));
    }
}
