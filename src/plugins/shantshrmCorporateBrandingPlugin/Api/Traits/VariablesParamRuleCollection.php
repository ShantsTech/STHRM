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
 * Boston, MA 02110-1301, USA
 */

namespace ShantsHRM\CorporateBranding\Api\Traits;

use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\CorporateBranding\Api\ValidationRules\Color;
use ShantsHRM\CorporateBranding\Service\ThemeService;

trait VariablesParamRuleCollection
{
    /**
     * @return ParamRuleCollection
     */
    protected function getParamRuleCollection(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                ThemeService::PRIMARY_COLOR,
                new Rule(Rules::STRING_TYPE),
                new Rule(Color::class)
            ),
            new ParamRule(
                ThemeService::PRIMARY_FONT_COLOR,
                new Rule(Rules::STRING_TYPE),
                new Rule(Color::class)
            ),
            new ParamRule(
                ThemeService::SECONDARY_COLOR,
                new Rule(Rules::STRING_TYPE),
                new Rule(Color::class)
            ),
            new ParamRule(
                ThemeService::SECONDARY_FONT_COLOR,
                new Rule(Rules::STRING_TYPE),
                new Rule(Color::class)
            ),
            new ParamRule(
                ThemeService::PRIMARY_GRADIENT_START_COLOR,
                new Rule(Rules::STRING_TYPE),
                new Rule(Color::class)
            ),
            new ParamRule(
                ThemeService::PRIMARY_GRADIENT_END_COLOR,
                new Rule(Rules::STRING_TYPE),
                new Rule(Color::class)
            ),
        );
    }
}
