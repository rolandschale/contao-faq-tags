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
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;
use Contao\ModuleModel;
use Contao\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @FrontendModule(value="faq_tag_list", category="faq", template="mod_faq_tag_list")
 */
class FaqTagListModule extends AbstractFrontendModuleController
{
    /**
     * @var DefaultManager
     */
    private $tagsManager;

    /**
     * FaqTagListModule constructor.
     */
    public function __construct(DefaultManager $tagsManager)
    {
        $this->tagsManager = $tagsManager;
    }

    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        // TODO: Implement getResponse() method.

        return new Response($template->parse());
    }
}
