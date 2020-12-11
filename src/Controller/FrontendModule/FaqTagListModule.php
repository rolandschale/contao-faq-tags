<?php

declare(strict_types=1);

/*
 * FAQ Tags Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2020, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\FaqTagsBundle\Controller\FrontendModule;

use Codefog\TagsBundle\Manager\DefaultManager;
use Codefog\TagsBundle\Tag;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\Input;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Template;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(value="faq_tag_list", category="faq", template="mod_faq_tag_list")
 */
class FaqTagListModule extends AbstractFrontendModuleController
{
    /**
     * Order
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
     * @var Connection
     */
    private $connection;

    /**
     * @var DefaultManager
     */
    private $tagsManager;

    /**
     * FaqTagListModule constructor.
     * @param Connection $connection
     * @param DefaultManager $tagsManager
     */
    public function __construct(Connection $connection, DefaultManager $tagsManager)
    {
        $this->connection = $connection;
        $this->tagsManager = $tagsManager;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        if (count($tags = $this->findTags($model)) === 0) {
            return new Response();
        }

        $template->tags = $this->generateTags($model, $tags);

        return new Response($template->parse());
    }

    /**
     * Find the tags.
     */
    protected function findTags(ModuleModel $model): array
    {
        $faqCategories = StringUtil::deserialize($model->faq_categories, true);

        if (!is_array($faqCategories) || count($faqCategories) === 0) {
            return [];
        }

        $faqIds = $this->connection->fetchAllAssociative(
            'SELECT id FROM tl_faq WHERE pid IN (?)' . ((!\defined('BE_USER_LOGGED_IN') || BE_USER_LOGGED_IN !== true) ? ' AND published=?' : ''),
            [$faqCategories, 1],
            [Connection::PARAM_INT_ARRAY]
        );

        if (count($faqIds) === 0) {
            return [];
        }

        $criteria = $this->tagsManager
            ->createTagCriteria()
            ->setSourceIds(array_column($faqIds, 'id'))
            ->setUsedOnly(true)
        ;

        $limit = $model->numberOfItems ? (int) $model->numberOfItems : null;

        if (count($tags = $this->tagsManager->getTagFinder()->getTopTags($criteria, $limit, true)) === 0) {
            return [];
        }

        return $this->sortTags($tags, $model->faq_tagsOrder);
    }

    /**
     * Sort the tags.
     */
    protected function sortTags(array $tags, string $order): array
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

    /**
     * Generate the tags.
     */
    protected function generateTags(ModuleModel $model, array $tags): array
    {
        // Generate the tags URL
        if ($model->jumpTo && ($page = PageModel::findPublishedById($model->jumpTo)) !== null) {
            $url = $page->getFrontendUrl('/' . self::URL_PARAMETER . '/%s');
        } else {
            $url = null;
        }

        $return = [];

        /** @var Tag $tag */
        foreach ($tags as $tag) {
            $return[] = $this->generateTag($tag, $url);
        }

        return $return;
    }

    /**
     * Generate a single tag.
     */
    protected function generateTag(Tag $tag, string $url = null): array
    {
        return [
            'name' => $tag->getName(),
            'count' => $tag->getData()['count'],
            'url' => ($url !== null) ? sprintf($url, $tag->getData()['alias']) : null,
            'isActive' => Input::get(self::URL_PARAMETER) === $tag->getData()['alias'],
        ];
    }
}
