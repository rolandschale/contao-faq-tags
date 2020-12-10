<?php

declare(strict_types=1);

/*
 * FAQ Tags Bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2020, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

/*
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['faq_tag_list'] = '{title_legend},name,headline,type;{config_legend},faq_categories,numberOfItems,faq_tagsOrder;{redirect_legend:hide},jumpTo;{template_legend:hide},customTpl;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

PaletteManipulator::create()
    ->addField('faq_allowTagFiltering', 'config_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('faqlist', 'tl_module')
    ->applyToPalette('faqpage', 'tl_module')
;

/*
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['faq_allowTagFiltering'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'clr'],
    'sql' => ['type' => 'boolean', 'unsigned' => 0],
];

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
