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

namespace ShantsHRM\Recruitment\Controller\PublicController;

use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\Exception\EndpointExceptionTrait;
use ShantsHRM\Core\Api\V2\Exception\NotImplementedException;
use ShantsHRM\Core\Api\V2\Exception\RecordNotFoundException;
use ShantsHRM\Core\Api\V2\Request;
use ShantsHRM\Core\Api\V2\Response;
use ShantsHRM\Core\Api\V2\Validator\Helpers\ValidationDecorator;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Controller\PublicControllerInterface;
use ShantsHRM\Core\Controller\Rest\V2\AbstractRestController;
use ShantsHRM\Core\Traits\Service\NormalizerServiceTrait;
use ShantsHRM\Entity\Vacancy;
use ShantsHRM\Recruitment\Api\Model\VacancyModel;
use ShantsHRM\Recruitment\Traits\Service\VacancyServiceTrait;

class VacancyRestController extends AbstractRestController implements PublicControllerInterface
{
    use VacancyServiceTrait;
    use NormalizerServiceTrait;
    use EndpointExceptionTrait;

    private const VACANCY_ID = 'id';
    /**
     * @var ValidationDecorator|null
     */
    private ?ValidationDecorator $validationDecorator = null;


    /**
     * @param Request $request
     * @return Response
     * @throws RecordNotFoundException
     */
    public function handleGetRequest(Request $request): Response
    {
        $vacancyId = $request->getAttributes()->getInt(self::VACANCY_ID);
        $vacancy = $this->getVacancyService()->getVacancyDao()->getVacancyById($vacancyId);
        if (!$vacancy instanceof Vacancy || !$vacancy->getDecorator()->isActiveAndPublished()) {
            throw $this->getRecordNotFoundException();
        }
        return new Response(
            $this->getNormalizerService()->normalize(VacancyModel::class, $vacancy)
        );
    }

    /**
     * @param Request $request
     * @return Response
     * @throws NotImplementedException
     */
    public function handlePostRequest(Request $request): Response
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @return NotImplementedException
     */
    private function getNotImplementedException(): NotImplementedException
    {
        return new NotImplementedException();
    }

    /**
     * @param Request $request
     * @return Response
     * @throws NotImplementedException
     */
    public function handlePutRequest(Request $request): Response
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @param Request $request
     * @return Response
     * @throws NotImplementedException
     */
    public function handleDeleteRequest(Request $request): Response
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @param Request $request
     * @return ParamRuleCollection|null
     */
    protected function initGetValidationRule(Request $request): ?ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                CommonParams::PARAMETER_ID,
                new Rule(Rules::POSITIVE)
            ),
        );
    }

    /**
     * @return ValidationDecorator
     */
    public function getValidationDecorator(): ValidationDecorator
    {
        if (!$this->validationDecorator instanceof ValidationDecorator) {
            $this->validationDecorator = new ValidationDecorator();
        }
        return $this->validationDecorator;
    }

    /**
     * @param Request $request
     * @return ParamRuleCollection|null
     * @throws NotImplementedException
     */
    public function initPostValidationRule(Request $request): ?ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @param Request $request
     * @return ParamRuleCollection|null
     * @throws NotImplementedException
     */
    public function initPutValidationRule(Request $request): ?ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @param Request $request
     * @return ParamRuleCollection|null
     * @throws NotImplementedException
     */
    public function initDeleteValidationRule(Request $request): ?ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }
}
