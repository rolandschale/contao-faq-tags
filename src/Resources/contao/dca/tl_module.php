<?php

declare(strict_types=1);

/*
 * FAQ Tags Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2020, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

/*
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['faq_tag_list'] = '{title_legend},name,headline,type;{config_legend},faq_categories,faq_tagsOrder,numberOfItems;{redirect_legend:hide},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

/*
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['faq_tagsOrder'] = [
    'exclude' => true,
    'inputType' => 'select',
    'options' => [
        \Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule::ORDER_NAME_ASC,
        \Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule::ORDER_NAME_DESC,
        \Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule::ORDER_COUNT_ASC,
        \Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule::ORDER_COUNT_DESC,
    ],
    'reference' => &$GLOBALS['TL_LANG']['tl_module']['faq_tagsOrderRef'],
    'eval' => ['tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 16, 'default' => \Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule::ORDER_NAME_ASC],
];
