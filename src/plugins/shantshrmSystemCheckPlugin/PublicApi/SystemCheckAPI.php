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

namespace ShantsHRM\SystemCheck\PublicApi;

use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\Model\ArrayModel;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\ResourceEndpoint;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Traits\LoggerTrait;
use ShantsHRM\Core\Traits\ORM\EntityManagerTrait;
use ShantsHRM\Core\Traits\Service\ConfigServiceTrait;
use ShantsHRM\Installer\Util\SystemCheck;
use Throwable;

class SystemCheckAPI extends Endpoint implements ResourceEndpoint
{
    use EntityManagerTrait;
    use ConfigServiceTrait;
    use LoggerTrait;

    public const PARAMETER_IS_INTERRUPTED = 'isInterrupted';

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        if (!$this->getConfigService()->showSystemCheckScreen()) {
            throw $this->getRecordNotFoundException();
        }
        try {
            $systemCheck = new SystemCheck($this->getEntityManager()->getConnection());
            return new EndpointResourceResult(
                ArrayModel::class,
                $systemCheck->getSystemCheckResults(),
                new ParameterBag([self::PARAMETER_IS_INTERRUPTED => $systemCheck->isInterruptContinue()])
            );
        } catch (Throwable $e) {
            try {
                $this->getLogger()->error($e->getMessage());
                $this->getLogger()->error($e->getTraceAsString());
            } finally {
                return new EndpointResourceResult(
                    ArrayModel::class,
                    [],
                    new ParameterBag([
                        self::PARAMETER_IS_INTERRUPTED => true,
                        'error' => ['message' => 'Unexpected Error Occurred'],
                    ])
                );
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        $paramRules = new ParamRuleCollection();
        $paramRules->addExcludedParamKey(CommonParams::PARAMETER_ID);
        return $paramRules;
    }

    /**
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function delete(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForDelete(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }
}
