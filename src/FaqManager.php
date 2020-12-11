<?php

namespace Codefog\FaqTagsBundle;

use Codefog\TagsBundle\Manager\DefaultManager;
use Codefog\TagsBundle\Tag;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Input;
use Contao\PageModel;

class FaqManager
{
    /**
     * Sorting order
     */
    const ORDER_NAME_ASC = 'name_asc';
    const ORDER_NAME_DESC = 'name_desc';
    const ORDER_COUNT_ASC = 'count_asc';
    const ORDER_COUNT_DESC = 'count_desc';

    /**
     * URL parameter
     */
    const URL_PARAMETER = 'tag';

    /**
     * @var ContaoFramework
     */
    private $framework;

    /**
     * @var DefaultManager
     */
    private $tagsManager;

    /**
     * FaqManager constructor.
     * @param ContaoFramework $framework
     * @param DefaultManager $tagsManager
     */
    public function __construct(ContaoFramework $framework, DefaultManager $tagsManager)
    {
        $this->framework = $framework;
        $this->tagsManager = $tagsManager;
    }

    /**
     * Generate multiple tags.
     */
    public function generateTags(array $tags, int $pageId = null): array
    {
        if (count($tags) === 0) {
            return [];
        }

        $return = [];

        /** @var Tag $tag */
        foreach ($tags as $tag) {
            $return[] = $this->generateTag($tag, $this->generateTagUrl($pageId));
        }

        return $return;
    }

    /**
     * Generate a single tag.
     */
    public function generateTag(Tag $tag, string $url = null): array
    {
        /** @var Input $inputAdapter */
        $inputAdapter = $this->framework->getAdapter(Input::class);

        $data = [
            'name' => $tag->getName(),
            'isActive' => $inputAdapter->get(self::URL_PARAMETER) === $tag->getData()['alias'],
        ];

        // Add the URL, if any
        if ($url !== null) {
            $data['url'] = sprintf($url, $tag->getData()['alias']);
        }

        $tagData = $tag->getData();

        // Add the tag count data, if available
        if (isset($tagData['count'])) {
            $data['count'] = $tagData['count'];
        }

        return $data;
    }

    /**
     * Generate the tag URL
     */
    public function generateTagUrl(int $pageId = null): ?string
    {
        static $cache = [];

        if (!array_key_exists($pageId, $cache)) {
            /** @var PageModel $pageModelAdapter */
            $pageModelAdapter = $this->framework->getAdapter(PageModel::class);

            if ($pageId !== null && ($pageModel = $pageModelAdapter->findPublishedById($pageId)) !== null) {
                $cache[$pageId] = $pageModel->getFrontendUrl('/' . self::URL_PARAMETER . '/%s');
            } else {
                $cache[$pageId] = null;
            }
        }

        return $cache[$pageId];
    }

    /**
     * Get the FAQ tags.
     */
    public function getFaqTags(int $faqId): array
    {
        return $this->tagsManager->getTagFinder()->findMultiple($this->tagsManager->createTagCriteria()->setSourceIds([$faqId]));
    }

    /**
     * Sort the tags.
     */
    public function sortTags(array $tags, string $order): array
    {
        switch ($order) {
            case self::ORDER_NAME_ASC:
                usort($tags, function (Tag $a, Tag $b): int {
                    return strnatcasecmp($a->getName(), $b->getName());
                });
                break;

            case self::ORDER_NAME_DESC:
                usort($tags, function (Tag $a, Tag $b): int {
                    return -strnatcasecmp($a->getName(), $b->getName());
                });
                break;

            case self::ORDER_COUNT_ASC:
                usort($tags, function (Tag $a, Tag $b): int {
                    $diff = $a->getData()['count'] - $b->getData()['count'];

                    // Sort the same value records alphabetically
                    if ($diff === 0) {
                        return strnatcasecmp($a->getName(), $b->getName());
                    }

                    return $diff;
                });
                break;

            case self::ORDER_COUNT_DESC:
                usort($tags, function (Tag $a, Tag $b): int {
                    $diff = $b->getData()['count'] - $a->getData()['count'];

                    // Sort the same value records alphabetically
                    if ($diff === 0) {
                        return strnatcasecmp($a->getName(), $b->getName());
                    }

                    return $diff;
                });
                break;
        }

        return $tags;
    }
}
