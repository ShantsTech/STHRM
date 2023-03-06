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

namespace ShantsHRM\Performance\Controller;

use ShantsHRM\Core\Authorization\Controller\CapableViewController;
use ShantsHRM\Core\Controller\AbstractVueController;
use ShantsHRM\Core\Controller\Common\NoRecordsFoundController;
use ShantsHRM\Core\Controller\Exception\RequestForwardableException;
use ShantsHRM\Core\Traits\UserRoleManagerTrait;
use ShantsHRM\Core\Vue\Component;
use ShantsHRM\Core\Vue\Prop;
use ShantsHRM\Entity\Employee;
use ShantsHRM\Entity\PerformanceReview;
use ShantsHRM\Framework\Http\Request;
use ShantsHRM\Performance\Traits\Service\PerformanceReviewServiceTrait;
use ShantsHRM\Pim\Traits\Service\EmployeeServiceTrait;

class AdminEvaluationController extends AbstractVueController implements CapableViewController
{
    use PerformanceReviewServiceTrait;
    use UserRoleManagerTrait;
    use EmployeeServiceTrait;

    /**
     * @inheritDoc
     */
    public function preRender(Request $request): void
    {
        if ($request->attributes->has('id')) {
            $id = $request->attributes->getInt('id');
            $component = new Component('admin-evaluation');
            $review = $this->getPerformanceReviewService()->getPerformanceReviewDao()->getPerformanceReviewById($id);
            if (!is_null($review)) {
                $this->setReviewProps($component, $review);
                if ($this->isUserPerformanceReviewEvaluator($id)) {
                    $component->addProp(new Prop('is-reviewer', Prop::TYPE_BOOLEAN, true));
                }
            }
            $this->setComponent($component);
        } else {
            throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }
    }

    /**
     * @inheritDoc
     */
    public function isCapable(Request $request): bool
    {
        $id = $request->attributes->getInt('id');
        $performanceReview = $this->getPerformanceReviewService()
            ->getPerformanceReviewDao()
            ->getPerformanceReviewById($id);
        if (is_null($performanceReview)
            || ($performanceReview->getEmployee() instanceof Employee
                && !is_null($performanceReview->getEmployee()->getPurgedAt()))) {
            throw new RequestForwardableException(NoRecordsFoundController::class . '::handle');
        }
        return $this->getUserRoleManager()->isEntityAccessible(PerformanceReview::class, $id, null, ['ESS']);
    }

    /**
     * @param Component $component
     * @param PerformanceReview $performanceReview
     */
    protected function setReviewProps(Component $component, PerformanceReview $performanceReview): void
    {
        $component->addProp(new Prop('review-id', Prop::TYPE_NUMBER, $performanceReview->getId()));
        $component->addProp(new Prop('status', Prop::TYPE_NUMBER, $performanceReview->getStatusId()));
        $component->addProp(new Prop('review-period-start', Prop::TYPE_STRING, $performanceReview->getDecorator()->getReviewPeriodStart()));
        $component->addProp(new Prop('review-period-end', Prop::TYPE_STRING, $performanceReview->getDecorator()->getReviewPeriodEnd()));
        $component->addProp(new Prop('due-date', Prop::TYPE_STRING, $performanceReview->getDecorator()->getDueDate()));
    }

    /**
     * @param int $performanceReviewId
     * @return bool
     */
    private function isUserPerformanceReviewEvaluator(int $performanceReviewId): bool
    {
        return $this->getUserRoleManager()->isEntityAccessible(
            PerformanceReview::class,
            $performanceReviewId,
            null,
            ['Admin', 'ESS'],
            ['Supervisor']
        );
    }
}
