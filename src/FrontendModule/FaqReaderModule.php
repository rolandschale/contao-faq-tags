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

use Contao\FaqModel;
use Contao\ModuleFaqReader;

class FaqReaderModule extends ModuleFaqReader
{
    use TagsTrait;

    /**
     * {@inheritDoc}
     */
    protected function compile(): void
    {
        parent::compile();

        // Add the tags
        if ($this->faq_showTags && ($faqModel = FaqModel::findByPk($this->Template->faq['id'])) !== null) {
            $this->Template->tags = $this->getFaqTags($faqModel, (int) $this->faq_tagsTargetPage);
        }
    }
}
