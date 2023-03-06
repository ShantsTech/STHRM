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

namespace ShantsHRM\Admin\Api;

use ShantsHRM\Admin\Api\Model\EmailSubscriptionModel;
use ShantsHRM\Admin\Dto\EmailSubscriptionSearchFilterParams;
use ShantsHRM\Entity\EmailNotification;
use ShantsHRM\Admin\Service\EmailSubscriptionService;
use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CrudEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\EndpointResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;

class EmailSubscriptionAPI extends Endpoint implements CrudEndpoint
{
    public const PARAMETER_ENABLED_STATUS = 'enabled';

    /**
     * @var null|EmailSubscriptionService
     */
    protected ?EmailSubscriptionService $emailSubscriptionService = null;

    /**
     * @return EmailSubscriptionService
     */
    protected function getEmailSubscriptionService(): EmailSubscriptionService
    {
        if (!$this->emailSubscriptionService instanceof EmailSubscriptionService) {
            $this->emailSubscriptionService = new EmailSubscriptionService();
        }
        return $this->emailSubscriptionService;
    }

    /**
     * @OA\Get(
     *     path="/api/v2/admin/email-subscriptions",
     *     tags={"Admin/Email Configuration"},
     *     @OA\Parameter(
     *         name="sortField",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum=EmailSubscriptionSearchFilterParams::ALLOWED_SORT_FIELDS)
     *     ),
     *     @OA\Parameter(ref="#/components/parameters/sortOrder"),
     *     @OA\Parameter(ref="#/components/parameters/limit"),
     *     @OA\Parameter(ref="#/components/parameters/offset"),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Admin-EmailSubscriptionModel")
     *             ),
     *             @OA\Property(property="meta",
     *                 type="object",
     *                 @OA\Property(property="total", type="integer")
     *             )
     *         )
     *     )
     * )
     * @inheritDoc
     */
    public function getAll(): EndpointResult
    {
        $emailSubscriptionSearchFilterParams = new EmailSubscriptionSearchFilterParams();
        $this->setSortingAndPaginationParams($emailSubscriptionSearchFilterParams);
        $emailSubscriptions = $this->getEmailSubscriptionService()
            ->getEmailSubscriptionDao()
            ->getEmailSubscriptions($emailSubscriptionSearchFilterParams);
        $emailSubscriptionsCount = $this->getEmailSubscriptionService()
            ->getEmailSubscriptionDao()
            ->getEmailSubscriptionListCount($emailSubscriptionSearchFilterParams);

        return new EndpointCollectionResult(
            EmailSubscriptionModel::class,
            $emailSubscriptions,
            new ParameterBag([CommonParams::PARAMETER_TOTAL => $emailSubscriptionsCount])
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            ...$this->getSortingAndPaginationParamsRules(EmailSubscriptionSearchFilterParams::ALLOWED_SORT_FIELDS)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
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

    /**
     * @inheritDoc
     */
    public function getOne(): EndpointResult
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetOne(): ParamRuleCollection
    {
        throw $this->getNotImplementedException();
    }

    /**
     * @OA\Put(
     *     path="/api/v2/admin/email-subscriptions/{id}",
     *     tags={"Admin/Email Configuration"},
     *     @OA\PathParameter(
     *         name="id",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="enabled", type="boolean"),
     *             required={"enabled"}
     *         )
     *     ),
     *     @OA\Response(response="200",
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="data",
     *                 ref="#/components/schemas/Admin-EmailSubscriptionModel"
     *             ),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     ),
     *     @OA\Response(response="404", ref="#/components/responses/RecordNotFound")
     * )
     *
     * @inheritDoc
     */
    public function update(): EndpointResult
    {
        $emailSubscriptionId = $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            CommonParams::PARAMETER_ID
        );
        $emailSubscription = $this->getEmailSubscriptionService()
            ->getEmailSubscriptionDao()
            ->getEmailSubscriptionById($emailSubscriptionId);
        $this->throwRecordNotFoundExceptionIfNotExist($emailSubscription, EmailNotification::class);
        $enabledStatus = $this->getRequestParams()->getBoolean(
            RequestParams::PARAM_TYPE_BODY,
            self::PARAMETER_ENABLED_STATUS
        );
        $emailSubscription->setEnabled($enabledStatus);
        $this->getEmailSubscriptionService()->getEmailSubscriptionDao()->saveEmailSubscription($emailSubscription);
        return new EndpointResourceResult(EmailSubscriptionModel::class, $emailSubscription);
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForUpdate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(CommonParams::PARAMETER_ID, new Rule(Rules::POSITIVE)),
            new ParamRule(
                self::PARAMETER_ENABLED_STATUS,
                new Rule(Rules::REQUIRED),
                new Rule(Rules::BOOL_TYPE)
            ),
        );
    }
}
