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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_module']['faq_tagsOrder'] = ['Tags order', 'Here you can choose the tags order.'];

/*
 * Reference
 */
$GLOBALS['TL_LANG']['tl_module']['faq_tagsOrderRef'] = [
    \Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule::ORDER_NAME_ASC => 'Name ascending',
    \Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule::ORDER_NAME_DESC => 'Name descending',
    \Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule::ORDER_COUNT_ASC => 'Count ascending',
    \Codefog\FaqTagsBundle\Controller\FrontendModule\FaqTagListModule::ORDER_COUNT_DESC => 'Count descending',
];
