<?php

namespace Codefog\TagsBundle\Test;

use Codefog\FaqTagsBundle\CodefogFaqTagsBundle;
use PHPUnit\Framework\TestCase;

class CodefogFaqTagsBundleTest extends TestCase
{
    public function testInstantiation()
    {
        $this->assertInstanceOf(CodefogFaqTagsBundle::class, new CodefogFaqTagsBundle());
    }
}
