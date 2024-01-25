<?php

namespace Extcode\Contacts\Domain\Repository;

/*
 * This file is part of the package extcode/contacts.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;

class ZipRepository extends Repository
{
    /**
     * @var array
     */
    protected $zipMap = [];

    protected function includeZipMap(string $zipMapFile): void
    {
        $zipMapFile = GeneralUtility::getFileAbsFileName($zipMapFile);

        if (file_exists(GeneralUtility::getFileAbsFileName($zipMapFile))) {
            $this->zipMap = include $zipMapFile;
            if (!is_array($zipMapFile)) {
                $zipMapFile = [];
            }
        }
    }

    public function findByCountryAndZip(string $country, string $zip, string $zipMapFile): array
    {
        $this->includeZipMap($zipMapFile);

        if (is_array($this->zipMap) && is_array($this->zipMap[$country]) && is_array($this->zipMap[$country][$zip])) {
            return $this->zipMap[$country][$zip];
        }
        return [];
    }
}
