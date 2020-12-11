<?php

namespace Codefog\FaqTagsBundle;

use Codefog\TagsBundle\Manager\DefaultManager;
use Codefog\TagsBundle\Tag;
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
     * @var DefaultManager
     */
    private $tagsManager;

    /**
     * FaqManager constructor.
     * @param DefaultManager $tagsManager
     */
    public function __construct(DefaultManager $tagsManager)
    {
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
        $data = [
            'name' => $tag->getName(),
            'isActive' => Input::get(self::URL_PARAMETER) === $tag->getData()['alias'],
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
            if ($pageId !== null && ($pageModel = PageModel::findPublishedById($pageId)) !== null) {
                $cache[$pageId] = $pageModel->getFrontendUrl('/' . self::URL_PARAMETER . '/%s');
            } else {
                return null;
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
                    if ($a->getData()['count'] === $b->getData()['count']) {
                        return 0;
                    }

                    return $a->getData()['count'] - $b->getData()['count'];
                });
                break;

            case self::ORDER_COUNT_DESC:
                usort($tags, function (Tag $a, Tag $b): int {
                    if ($a->getData()['count'] === $b->getData()['count']) {
                        return 0;
                    }

                    return $b->getData()['count'] - $a->getData()['count'];
                });
                break;
        }

        return $tags;
    }
}
