<?php
defined('TYPO3') || die;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

$myTable = 'tx_contacts_domain_model_company';
$_LLL_db = 'LLL:EXT:contacts/Resources/Private/Language/locallang_db.xlf:';

$GLOBALS['TCA'][$myTable]['columns']['category'] = [
    'exclude' => 1,
    'label' => $_LLL_db . 'tx_contacts_domain_model_company.category',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectTree',
        'foreign_table' => 'sys_category',
        'foreign_table_where' => ' AND sys_category.sys_language_uid IN (-1, 0)',
        'minitems' => 0,
        'maxitems' => 1,
        'treeConfig' => [
            'parentField' => 'parent',
            'appearance' => [
                'expandAll' => true,
                'showHeader' => true,
            ],
        ],
    ],
];

$GLOBALS['TCA'][$myTable]['columns']['categories'] = [
    'exclude' => 1,
    'label' => $_LLL_db . 'tx_contacts_domain_model_company.categories',
    'config' => [
        'type' => 'select',
        'renderType' => 'selectTree',
        'foreign_table' => 'sys_category',
        'foreign_table_where' => ' AND sys_category.sys_language_uid IN (-1, 0)',
        'MM' => 'sys_category_record_mm',
        'MM_match_fields' => [
            'fieldname' => 'categories',
            'tablenames' => $myTable,
        ],
        'MM_opposite_field' => 'items',
        'minitems' => 0,
        'maxitems' => 9999,
        'treeConfig' => [
            'parentField' => 'parent',
            'appearance' => [
                'expandAll' => true,
                'showHeader' => true,
            ],
        ],
    ],
];

ExtensionManagementUtility::addToAllTCAtypes($myTable, 'category, categories', '', 'after:description');


// category restriction based on settings in extension manager
$categoryRestrictionSetting = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('contacts', 'categoryRestriction');

if ($categoryRestrictionSetting) {
    $categoryRestriction = '';
    switch ($categoryRestrictionSetting) {
        case 'current_pid':
            $categoryRestriction = ' AND sys_category.pid=###CURRENT_PID### ';
            break;
        case 'siteroot':
            $categoryRestriction = ' AND sys_category.pid IN (###SITEROOT###) ';
            break;
        case 'page_tsconfig':
            $categoryRestriction = ' AND sys_category.pid IN (###PAGE_TSCONFIG_IDLIST###) ';
            break;
        default:
            $categoryRestriction = '';
    }

    // prepend category restriction at the beginning of foreign_table_where
    if (!empty($categoryRestriction)) {
        $GLOBALS['TCA']['tx_contacts_domain_model_company']['columns']['category']['config']['foreign_table_where'] = $categoryRestriction .
            $GLOBALS['TCA']['tx_contacts_domain_model_company']['columns']['category']['config']['foreign_table_where'];
        $GLOBALS['TCA']['tx_contacts_domain_model_company']['columns']['categories']['config']['foreign_table_where'] = $categoryRestriction .
            $GLOBALS['TCA']['tx_contacts_domain_model_company']['columns']['categories']['config']['foreign_table_where'];
    }
}
