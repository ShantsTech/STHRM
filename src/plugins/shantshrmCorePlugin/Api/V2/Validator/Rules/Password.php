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

namespace ShantsHRM\Core\Api\V2\Validator\Rules;

use ShantsHRM\Core\Traits\Service\TextHelperTrait;

class Password extends AbstractRule
{
    use TextHelperTrait;

    private bool $changePassword;

    private const UPPERCASE_REGEX = '/[A-Z]/';
    private const LOWERCASE_REGEX = '/[a-z]/';
    private const NUMBER_REGEX = '/[0-9]/';
    private const SPECIAL_CHAR_REGEX = '/[@#\\\\\/\-!$%^&*()_+|~=`{}\[\]:";\'<>?,.]/';

    public function __construct(?bool $changePassword)
    {
        $this->changePassword = $changePassword ?? true;
    }

    public function validate($input): bool
    {
        if (!$this->changePassword) {
            return true;
        }

        $uppercaseMatch = preg_match(self::UPPERCASE_REGEX, $input);
        $lowercaseMatch = preg_match(self::LOWERCASE_REGEX, $input);
        $numberMatch = preg_match(self::NUMBER_REGEX, $input);
        $specialCharMatch = preg_match(self::SPECIAL_CHAR_REGEX, $input);

        return (
            $uppercaseMatch > 0 &&
            $lowercaseMatch > 0 &&
            $numberMatch > 0 &&
            $specialCharMatch > 0 &&
            $this->getTextHelper()->strLength($input) >= 8
        );
    }
}
