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

use Codefog\FaqTagsBundle\FaqManager;
use Codefog\TagsBundle\Manager\DefaultManager;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
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
     * @var Connection
     */
    private $connection;

    /**
     * @var FaqManager
     */
    private $faqManager;

    /**
     * @var DefaultManager
     */
    private $tagsManager;

    /**
     * FaqTagListModule constructor.
     */
    public function __construct(Connection $connection, FaqManager $faqManager, DefaultManager $tagsManager)
    {
        $this->connection = $connection;
        $this->faqManager = $faqManager;
        $this->tagsManager = $tagsManager;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        if (0 === \count($tags = $this->findTags($model))) {
            return new Response();
        }

        $template->tags = $this->faqManager->generateTags($tags, (int) $model->faq_tagsTargetPage);

        return new Response($template->parse());
    }

    /**
     * Find the tags.
     */
    protected function findTags(ModuleModel $model): array
    {
        $faqCategories = StringUtil::deserialize($model->faq_categories, true);

        if (!\is_array($faqCategories) || 0 === \count($faqCategories)) {
            return [];
        }

        $faqIds = $this->connection->fetchAllAssociative(
            'SELECT id FROM tl_faq WHERE pid IN (?)'.((!\defined('BE_USER_LOGGED_IN') || BE_USER_LOGGED_IN !== true) ? ' AND published=?' : ''),
            [$faqCategories, 1],
            [Connection::PARAM_INT_ARRAY]
        );

        if (0 === \count($faqIds)) {
            return [];
        }

        $criteria = $this->tagsManager
            ->createTagCriteria()
            ->setSourceIds(array_column($faqIds, 'id'))
            ->setUsedOnly(true)
        ;

        $limit = $model->numberOfItems ? (int) $model->numberOfItems : null;

        if (0 === \count($tags = $this->tagsManager->getTagFinder()->getTopTags($criteria, $limit, true))) {
            return [];
        }

        return $this->faqManager->sortTags($tags, $model->faq_tagsOrder);
    }
}
