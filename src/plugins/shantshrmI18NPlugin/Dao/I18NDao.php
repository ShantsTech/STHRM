<?php
/**
 * ShantsHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 ShantsHRM Inc., http://www.hrm.shants-tech.com
 *
 * ShantsHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * ShantsHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

namespace ShantsHRM\I18N\Dao;

use Doctrine\ORM\Query\Expr;
use InvalidArgumentException;
use ShantsHRM\Core\Dao\BaseDao;
use ShantsHRM\Entity\I18NLangString;
use ShantsHRM\Entity\I18NLanguage;
use ShantsHRM\ORM\QueryBuilderWrapper;

class I18NDao extends BaseDao
{
    /**
     * @param string $langCode
     * @return I18NLanguage|null
     */
    public function getLanguageByLangCode(string $langCode): ?I18NLanguage
    {
        return $this->getRepository(I18NLanguage::class)->findOneBy(['code' => $langCode, 'enabled' => true]);
    }

    public function getAllTranslationMessagesByLangCode(string $langCode)
    {
        $q = $this->createQueryBuilderWrapperForAllTranslationByLangCode($langCode)->getQueryBuilder();
        $q->leftJoin('langString.group', 'module');
        $q->select(
            'langString.unitId',
            'langString.value AS source',
            'translation.value AS target',
            'module.name AS groupName',
        );

        return $q->getQuery()->getArrayResult();
    }

    /**
     * @param string $langCode
     * @return QueryBuilderWrapper
     */
    private function createQueryBuilderWrapperForAllTranslationByLangCode(
        string $langCode
    ): QueryBuilderWrapper {
        $language = $this->getLanguageByLangCode($langCode);
        if (!$language instanceof I18NLanguage) {
            throw new InvalidArgumentException("Invalid locale: $langCode");
        }

        $q = $this->createQueryBuilder(I18NLangString::class, 'langString');
        $q->leftJoin(
            'langString.translations',
            'translation',
            Expr\Join::WITH,
            'IDENTITY(translation.language) = :langId'
        );

        $q->setParameter('langId', $language->getId());
        return $this->getQueryBuilderWrapper($q);
    }
}
