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

namespace ShantsHRM\Core\Service;

class NumberHelperService
{
    /**
     * @param float $num
     * @param int $decimals
     * @return string
     * @link https://www.php.net/manual/en/function.number-format.php
     */
    public function numberFormat(float $num, int $decimals = 0): string
    {
        return number_format($num, $decimals, '.', '');
    }

    /**
     * @param float $num
     * @param int $decimals
     * @return string
     * @link https://www.php.net/manual/en/function.number-format.php
     */
    public function numberFormatWithGroupedThousands(
        float $num,
        int $decimals = 0,
        ?string $thousandsSeparator = ','
    ): string {
        return number_format($num, $decimals, '.', $thousandsSeparator);
    }
}
