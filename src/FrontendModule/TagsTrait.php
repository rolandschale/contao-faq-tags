<?php

namespace Codefog\FaqTagsBundle\FrontendModule;

use Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule;
use Codefog\TagsBundle\Manager\DefaultManager;
use Codefog\TagsBundle\Tag;
use Contao\CoreBundle\Exception\PageNotFoundException;
use Contao\FaqModel;
use Contao\Input;
use Contao\Model\Collection;
use Contao\Module;
use Contao\System;

/**
 * @internal
 */
trait TagsTrait
{
    /**
     * Get the FAQ items
     */
    private function getFaqItems(Module $module): ?Collection
    {
        if (!$module->faq_allowTagFiltering || ($tag = $this->getCurrentTag()) === null) {
            return FaqModel::findPublishedByPids($this->faq_categories);
        }

        $tagManager = $this->getTagManager();
        $faqIds = $tagManager->getSourceFinder()->findMultiple($tagManager->createSourceCriteria()->setTag($tag));

        // Return if there are no FAQ items matching the tag
        if (count($faqIds) === 0) {
            return null;
        }

        $columns = [
            'id IN (' . implode(',', array_map('\intval', $faqIds)) . ')',
            'pid IN(' . implode(',', array_map('\intval', $this->faq_categories)) . ')'
        ];

        $values = [];

        if (!\defined('BE_USER_LOGGED_IN') || BE_USER_LOGGED_IN !== true) {
            $columns[] = 'published=?';
            $values[] = 1;
        }

        return FaqModel::findBy($columns, $values, ['order' => 'pid, sorting']);
    }

    /**
     * Get the current tag.
     */
    private function getCurrentTag(): ?Tag
    {
        if (!($tagAlias = Input::get(FaqTagListModule::URL_PARAMETER))) {
            return null;
        }

        $tagManager = $this->getTagManager();
        $tag = $tagManager->getTagFinder()->findSingle($tagManager->createTagCriteria()->setAlias($tagAlias));

        if ($tag === null) {
            throw new PageNotFoundException(sprintf('Tag with alias "%s" does not exist', $tagAlias));
        }

        return $tag;
    }

    /**
     * Get the tag manager
     */
    private function getTagManager(): DefaultManager
    {
        /** @var DefaultManager $tagManager */
        $tagManager = System::getContainer()->get('codefog_tags.manager.codefog_faq');

        return $tagManager;
    }
}
