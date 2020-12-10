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

use Contao\FaqCategoryModel;
use Contao\ModuleFaqList;
use Contao\StringUtil;

class FaqListModule extends ModuleFaqList
{
    use TagsTrait;

    /**
     * {@inheritDoc}
     */
    protected function compile(): void
    {
        if (null === ($objFaq = $this->getFaqItems($this))) {
            $this->Template->faq = [];

            return;
        }

        $arrFaq = array_fill_keys($this->faq_categories, []);

        // Add FAQs
        while ($objFaq->next()) {
            $arrTemp = $objFaq->row();
            $arrTemp['title'] = StringUtil::specialchars($objFaq->question, true);
            $arrTemp['href'] = $this->generateFaqLink($objFaq);

            /** @var FaqCategoryModel $objPid */
            $objPid = $objFaq->getRelated('pid');

            $arrFaq[$objFaq->pid]['items'][] = $arrTemp;
            $arrFaq[$objFaq->pid]['headline'] = $objPid->headline;
            $arrFaq[$objFaq->pid]['title'] = $objPid->title;
        }

        $arrFaq = array_values(array_filter($arrFaq));

        $cat_count = 0;
        $cat_limit = \count($arrFaq);

        // Add classes
        foreach ($arrFaq as $k => $v) {
            $count = 0;
            $limit = \count($v['items']);

            for ($i = 0; $i < $limit; ++$i) {
                $arrFaq[$k]['items'][$i]['class'] = trim(((1 === ++$count) ? ' first' : '').(($count >= $limit) ? ' last' : '').((($count % 2) === 0) ? ' odd' : ' even'));
            }

            $arrFaq[$k]['class'] = trim(((1 === ++$cat_count) ? ' first' : '').(($cat_count >= $cat_limit) ? ' last' : '').((($cat_count % 2) === 0) ? ' odd' : ' even'));
        }

        $this->Template->faq = $arrFaq;
    }
}
