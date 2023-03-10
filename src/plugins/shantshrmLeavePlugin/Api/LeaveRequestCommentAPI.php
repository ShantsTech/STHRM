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

namespace ShantsHRM\Leave\Api;

use Exception;
use ShantsHRM\Core\Api\CommonParams;
use ShantsHRM\Core\Api\V2\CollectionEndpoint;
use ShantsHRM\Core\Api\V2\Endpoint;
use ShantsHRM\Core\Api\V2\EndpointCollectionResult;
use ShantsHRM\Core\Api\V2\EndpointResourceResult;
use ShantsHRM\Core\Api\V2\ParameterBag;
use ShantsHRM\Core\Api\V2\RequestParams;
use ShantsHRM\Core\Api\V2\Validator\ParamRule;
use ShantsHRM\Core\Api\V2\Validator\ParamRuleCollection;
use ShantsHRM\Core\Api\V2\Validator\Rule;
use ShantsHRM\Core\Api\V2\Validator\Rules;
use ShantsHRM\Core\Traits\Auth\AuthUserTrait;
use ShantsHRM\Core\Traits\ORM\EntityManagerHelperTrait;
use ShantsHRM\Core\Traits\Service\DateTimeHelperTrait;
use ShantsHRM\Entity\LeaveRequest;
use ShantsHRM\Entity\LeaveRequestComment;
use ShantsHRM\Leave\Api\Model\LeaveRequestCommentModel;
use ShantsHRM\Leave\Api\Traits\LeaveRequestPermissionTrait;
use ShantsHRM\Leave\Dto\LeaveRequestCommentSearchFilterParams;
use ShantsHRM\Leave\Service\LeaveRequestCommentService;

class LeaveRequestCommentAPI extends Endpoint implements CollectionEndpoint
{
    use DateTimeHelperTrait;
    use EntityManagerHelperTrait;
    use AuthUserTrait;
    use LeaveRequestPermissionTrait;

    public const PARAMETER_LEAVE_REQUEST_ID = 'leaveRequestId';
    public const PARAMETER_COMMENT = 'comment';

    public const PARAM_RULE_COMMENT_MAX_LENGTH = 255;
    /**
     * @var null|LeaveRequestCommentService
     */
    protected ?LeaveRequestCommentService $leaveRequestCommentService = null;

    /**
     * @return LeaveRequestCommentService
     */
    public function getLeaveRequestCommentService(): LeaveRequestCommentService
    {
        if (is_null($this->leaveRequestCommentService)) {
            $this->leaveRequestCommentService = new LeaveRequestCommentService();
        }
        return $this->leaveRequestCommentService;
    }

    /**
     * @return int|null
     */
    private function getUrlAttributes(): ?int
    {
        return $this->getRequestParams()->getInt(
            RequestParams::PARAM_TYPE_ATTRIBUTE,
            self::PARAMETER_LEAVE_REQUEST_ID
        );
    }

    /**
     * @inheritdoc
     */
    public function getAll(): EndpointCollectionResult
    {
        $leaveRequestId = $this->getUrlAttributes();

        /** @var LeaveRequest|null $leaveRequest */
        $leaveRequest = $this->getLeaveRequestCommentService()->getLeaveRequestCommentDao()
            ->getLeaveRequestById($leaveRequestId);

        $this->throwRecordNotFoundExceptionIfNotExist($leaveRequest, LeaveRequest::class);

        $this->checkLeaveRequestAccessible($leaveRequest);

        $leaveRequestCommentSearchFilterParams = new LeaveRequestCommentSearchFilterParams();

        $leaveRequestCommentSearchFilterParams->setLeaveRequestId($leaveRequestId);
        $this->setSortingAndPaginationParams($leaveRequestCommentSearchFilterParams);

        $leaveRequestComments = $this->getLeaveRequestCommentService()->getLeaveRequestCommentDao()
            ->searchLeaveRequestComments($leaveRequestCommentSearchFilterParams);
        return new EndpointCollectionResult(
            LeaveRequestCommentModel::class,
            $leaveRequestComments,
            new ParameterBag(
                [
                    CommonParams::PARAMETER_TOTAL => $this->getLeaveRequestCommentService()->getLeaveRequestCommentDao()
                        ->getSearchLeaveRequestCommentsCount($leaveRequestCommentSearchFilterParams)
                ]
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForGetAll(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            ...$this->getCommonValidationRules(),
            ...$this->getSortingAndPaginationParamsRules(LeaveRequestCommentSearchFilterParams::ALLOWED_SORT_FIELDS)
        );
    }

    /**
     * @inheritDoc
     */
    public function create(): EndpointResourceResult
    {
        $leaveRequestId = $this->getUrlAttributes();

        /** @var LeaveRequest|null $leaveRequest */
        $leaveRequest = $this->getLeaveRequestCommentService()->getLeaveRequestCommentDao()
            ->getLeaveRequestById($leaveRequestId);

        $this->throwRecordNotFoundExceptionIfNotExist($leaveRequest, LeaveRequest::class);

        $this->checkLeaveRequestAccessible($leaveRequest);

        $leaveRequestComment = new LeaveRequestComment();
        $leaveRequestComment->getDecorator()->setLeaveRequestById($leaveRequestId);
        $this->setLeaveRequestComment($leaveRequestComment);
        $leaveRequestComment = $this->saveLeaveRequestComment($leaveRequestComment);
        return new EndpointResourceResult(
            LeaveRequestCommentModel::class,
            $leaveRequestComment,
        );
    }

    /**
     * @inheritDoc
     */
    public function getValidationRuleForCreate(): ParamRuleCollection
    {
        return new ParamRuleCollection(
            new ParamRule(
                self::PARAMETER_COMMENT,
                new Rule(Rules::STRING_TYPE),
                new Rule(Rules::LENGTH, [null, self::PARAM_RULE_COMMENT_MAX_LENGTH]),
            ),
            ...$this->getCommonValidationRules()
        );
    }

    /**
     * @return ParamRule[]
     */
    private function getCommonValidationRules(): array
    {
        return [
            new ParamRule(
                self::PARAMETER_LEAVE_REQUEST_ID,
                new Rule(Rules::POSITIVE)
            )
        ];
    }

    /**
     * @param LeaveRequestComment $leaveRequestComment
     */
    private function setLeaveRequestComment(LeaveRequestComment $leaveRequestComment): void
    {
        $comment = $this->getRequestParams()->getString(
            RequestParams::PARAM_TYPE_BODY,
            self::PARAMETER_COMMENT
        );
        $leaveRequestComment->setComment($comment);
        $leaveRequestComment->setCreatedAt($this->getDateTimeHelper()->getNow());
        $leaveRequestComment->getDecorator()->setCreatedByEmployeeByEmpNumber($this->getAuthUser()->getEmpNumber());
        $leaveRequestComment->getDecorator()->setCreatedByUserById($this->getAuthUser()->getUserId());
    }

    /**
     * @param LeaveRequestComment $leaveRequestComment
     * @return LeaveRequestComment
     * @throws Exception
     */
    private function saveLeaveRequestComment(LeaveRequestComment $leaveRequestComment): LeaveRequestComment
    {
        return $this->getLeaveRequestCommentService()
            ->getLeaveRequestCommentDao()
            ->saveLeaveRequestComment($leaveRequestComment);
    }

    /**
     * @inheritDoc
     */
    public function delete(): EndpointResourceResult
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
