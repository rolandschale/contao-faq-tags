<?php

declare(strict_types=1);

/*
 * FAQ Tags Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2020, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\TagsBundle\Test;

use Codefog\FaqTagsBundle\CodefogFaqTagsBundle;
use PHPUnit\Framework\TestCase;

class CodefogFaqTagsBundleTest extends TestCase
{
    public function testInstantiation(): void
    {
        $this->assertInstanceOf(CodefogFaqTagsBundle::class, new CodefogFaqTagsBundle());
    }
}
