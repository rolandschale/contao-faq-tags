<?php

declare(strict_types=1);

/*
 * FAQ Tags Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2020, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\FaqTagsBundle\FrontendModule;

use Contao\ModuleFaqReader;

class FaqReaderModule extends ModuleFaqReader
{
    /**
     * {@inheritDoc}
     */
    protected function compile(): void
    {
        parent::compile();

        // @todo – add tags to template
    }
}
