<?php

namespace Codefog\FaqTagsBundle\FrontendModule;

use Codefog\FaqTagsBundle\FaqManager;
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
     * Get the FAQ tags.
     */
    protected function getFaqTags(FaqModel $faqModel, int $pageId = null): array
    {
        $faqManager = $this->getFaqManager();

        $tags = $faqManager->getFaqTags((int) $faqModel->id);
        $tags = $faqManager->sortTags($tags, FaqManager::ORDER_NAME_ASC);

        return $faqManager->generateTags($tags, $pageId);
    }

    /**
     * Get the FAQ items
     */
    protected function getFaqItems(Module $module): ?Collection
    {
        if (!$module->faq_allowTagFiltering || ($tag = $this->getCurrentTag()) === null) {
            return FaqModel::findPublishedByPids($this->faq_categories);
        }

        $tagManager = $this->getTagsManager();
        $faqIds = $tagManager->getSourceFinder()->findMultiple($tagManager->createSourceCriteria()->setTag($tag));

        // Return if there are no FAQ items matching the tag
        if (count($faqIds) === 0) {
            return null;
        }

        $columns = [
            'id IN (' . implode(',', array_map('\intval', $faqIds)) . ')',
            'pid IN(' . implode(',', array_map('\intval', $this->faq_categories)) . ')',
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
    protected function getCurrentTag(): ?Tag
    {
        if (!($tagAlias = Input::get(FaqManager::URL_PARAMETER))) {
            return null;
        }

        $tagManager = $this->getTagsManager();
        $tag = $tagManager->getTagFinder()->findSingle($tagManager->createTagCriteria()->setAlias($tagAlias));

        if ($tag === null) {
            throw new PageNotFoundException(sprintf('Tag with alias "%s" does not exist', $tagAlias));
        }

        return $tag;
    }

    /**
     * Get the FAQ manager
     */
    protected function getFaqManager(): FaqManager
    {
        /** @var FaqManager $faqManager */
        $faqManager = System::getContainer()->get('codefog_faq_tags.faq_manager');

        return $faqManager;
    }

    /**
     * Get the tags manager
     */
    protected function getTagsManager(): DefaultManager
    {
        /** @var DefaultManager $tagManager */
        $tagManager = System::getContainer()->get('codefog_tags.manager.codefog_faq');

        return $tagManager;
    }
}
