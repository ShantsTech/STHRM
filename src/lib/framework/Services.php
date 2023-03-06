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

namespace ShantsHRM\Framework;

final class Services
{
    /**
     * @see \ShantsHRM\Framework\Http\RequestStack
     */
    public const REQUEST_STACK = 'request_stack';

    /**
     * @see \ShantsHRM\Framework\Routing\RequestContext
     */
    public const ROUTER_REQUEST_CONTEXT = 'router.request_context';

    /**
     * @see \ShantsHRM\Framework\Routing\UrlMatcher
     */
    public const ROUTER = 'router.default';

    /**
     * @see \ShantsHRM\Framework\Event\EventDispatcher
     */
    public const EVENT_DISPATCHER = 'event_dispatcher';

    /**
     * @see \ShantsHRM\Framework\Http\ControllerResolver
     */
    public const CONTROLLER_RESOLVER = 'controller_resolver';

    /**
     * @see \Symfony\Component\HttpKernel\Controller\ArgumentResolver
     */
    public const ARGUMENT_RESOLVER = 'argument_resolver';

    /**
     * @see \ShantsHRM\Framework\Framework
     */
    public const HTTP_KERNEL = 'http_kernel';

    /**
     * @see \ShantsHRM\Framework\Http\Session\NativeSessionStorage
     */
    public const SESSION_STORAGE = 'session_storage';

    /**
     * @see \ShantsHRM\Framework\Http\Session\Session
     */
    public const SESSION = 'session';

    /**
     * @see \ShantsHRM\Framework\Logger\Logger
     */
    public const LOGGER = 'logger';

    /**
     * @see \ShantsHRM\Framework\Routing\UrlGenerator
     */
    public const URL_GENERATOR = 'url_generator';

    /**
     * @see \Symfony\Component\HttpFoundation\UrlHelper
     */
    public const URL_HELPER = 'url_helper';

    /**
     * @see \Doctrine\ORM\EntityManager
     */
    public const DOCTRINE = 'doctrine.entity_manager';

    ///////////////////////////////////////////////////////////////
    /// Core plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Core\Service\ConfigService
     */
    public const CONFIG_SERVICE = 'core.config_service';

    /**
     * @see \ShantsHRM\Core\Service\NormalizerService
     */
    public const NORMALIZER_SERVICE = 'core.normalizer_service';

    /**
     * @see \ShantsHRM\Core\Service\DateTimeHelperService
     */
    public const DATETIME_HELPER_SERVICE = 'core.datetime_helper_service';

    /**
     * @see \ShantsHRM\Core\Service\TextHelperService
     */
    public const TEXT_HELPER_SERVICE = 'core.text_helper_service';

    /**
     * @see \ShantsHRM\Core\Service\TextHelperService
     */
    public const NUMBER_HELPER_SERVICE = 'core.number_helper_service';

    /**
     * @see \ShantsHRM\Core\Helper\ClassHelper
     */
    public const CLASS_HELPER = 'core.class_helper';

    /**
     * @see \ShantsHRM\Core\Authorization\Manager\AbstractUserRoleManager
     */
    public const USER_ROLE_MANAGER = 'core.authorization.user_role_manager';

    /**
     * @see \ShantsHRM\Core\Authorization\Helper\UserRoleManagerHelper
     */
    public const USER_ROLE_MANAGER_HELPER = 'core.authorization.user_role_manager_helper';

    /**
     * @see \ShantsHRM\Framework\Cache\FilesystemAdapter
     * @see \Symfony\Component\Cache\Adapter\AdapterInterface
     */
    public const CACHE = 'core.cache';

    /**
     * @see \ShantsHRM\Core\Service\MenuService
     */
    public const MENU_SERVICE = 'core.menu_service';

    /**
     * @see \ShantsHRM\Core\Service\ReportGeneratorService
     */
    public const REPORT_GENERATOR_SERVICE = 'core.report_generator_service';

    /**
     * @see \ShantsHRM\Core\Service\ModuleService
     */
    public const MODULE_SERVICE = 'core.module_service';

    ///////////////////////////////////////////////////////////////
    /// Authentication plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Authentication\Auth\User
     */
    public const AUTH_USER = 'auth.user';

    /**
     * @see \ShantsHRM\Authentication\Auth\AuthProviderChain
     */
    public const AUTH_PROVIDER_CHAIN = 'auth.provider_chain';

    /**
     * @see \ShantsHRM\Authentication\Csrf\CsrfTokenManager
     */
    public const CSRF_TOKEN_MANAGER = 'auth.csrf_token_manager';

    /**
     * @see \Symfony\Component\Security\Csrf\TokenStorage\NativeSessionTokenStorage
     * @see \Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface
     */
    public const CSRF_TOKEN_STORAGE = 'auth.csrf_token_storage';

    ///////////////////////////////////////////////////////////////
    /// Admin plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Admin\Service\CountryService
     */
    public const COUNTRY_SERVICE = 'admin.country_service';

    /**
     * @see \ShantsHRM\Admin\Service\PayGradeService
     */
    public const PAY_GRADE_SERVICE = 'admin.pay_grade_service';

    /**
     * @see \ShantsHRM\Admin\Service\UserService
     */
    public const USER_SERVICE = 'admin.user_service';

    /**
     * @see \ShantsHRM\Admin\Service\CompanyStructureService
     */
    public const COMPANY_STRUCTURE_SERVICE = 'admin.company_structure_service';

    /**
     * @see \ShantsHRM\Admin\Service\WorkShiftService
     */
    public const WORK_SHIFT_SERVICE = 'admin.work_shift_service';

    /**
     * @see \ShantsHRM\Admin\Service\LocalizationService
     */
    public const LOCALIZATION_SERVICE = 'admin.localization_service';

    /**
     * @see \ShantsHRM\CorporateBranding\Service\ThemeService
     */
    public const THEME_SERVICE = 'admin.theme_service';

    ///////////////////////////////////////////////////////////////
    /// Leave plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Leave\Service\LeaveConfigurationService
     */
    public const LEAVE_CONFIG_SERVICE = 'leave.leave_config_service';

    /**
     * @see \ShantsHRM\Leave\Service\LeaveTypeService
     */
    public const LEAVE_TYPE_SERVICE = 'leave.leave_type_service';

    /**
     * @see \ShantsHRM\Leave\Service\LeaveEntitlementService
     */
    public const LEAVE_ENTITLEMENT_SERVICE = 'leave.leave_entitlement_service';

    /**
     * @see \ShantsHRM\Leave\Service\LeavePeriodService
     */
    public const LEAVE_PERIOD_SERVICE = 'leave.leave_period_service';

    /**
     * @see \ShantsHRM\Leave\Service\LeaveRequestService
     */
    public const LEAVE_REQUEST_SERVICE = 'leave.leave_request_service';

    /**
     * @see \ShantsHRM\Leave\Service\WorkScheduleService
     */
    public const WORK_SCHEDULE_SERVICE = 'leave.work_schedule_service';

    /**
     * @see \ShantsHRM\Leave\Service\HolidayService
     */
    public const HOLIDAY_SERVICE = 'leave.holiday_service';

    /**
     * @see \ShantsHRM\Leave\Service\WorkWeekService
     */
    public const WORK_WEEK_SERVICE = 'leave.work_week_service';

    ///////////////////////////////////////////////////////////////
    /// Pim plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Pim\Service\EmployeeService
     */
    public const EMPLOYEE_SERVICE = 'pim.employee_service';

    /**
     * @see \ShantsHRM\Pim\Service\EmployeeSalaryService
     */
    public const EMPLOYEE_SALARY_SERVICE = 'pim.employee_salary_service';

    ///////////////////////////////////////////////////////////////
    /// Time plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Time\Service\ProjectService
     */
    public const PROJECT_SERVICE = 'time.project_service';

    /**
     * @see \ShantsHRM\Time\Service\CustomerService
     */
    public const CUSTOMER_SERVICE = 'time.customer_service';

    /**
     * @see \ShantsHRM\Time\Service\TimesheetService
     */
    public const TIMESHEET_SERVICE = 'time.timesheet_service';

    ///////////////////////////////////////////////////////////////
    /// Attendance plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Attendance\Service\AttendanceService
     */
    public const ATTENDANCE_SERVICE = 'attendance.attendance_service';

    ///////////////////////////////////////////////////////////////
    /// I18N plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\I18N\Service\I18NService
     */
    public const I18N_SERVICE = 'i18n.i18n_service';

    /**
     * @see \ShantsHRM\I18N\Service\I18NHelper
     */
    public const I18N_HELPER = 'i18n.i18n_helper';

    ///////////////////////////////////////////////////////////////
    /// Recruitment plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Recruitment\Service\VacancyService
     */
    public const VACANCY_SERVICE = 'recruitment.vacancy_service';

    /**
     * @see \ShantsHRM\Recruitment\Service\RecruitmentAttachmentService
     */
    public const RECRUITMENT_ATTACHMENT_SERVICE = 'recruitment.recruitment_attachment_service';

    /**
     * @see \ShantsHRM\Recruitment\Service\RecruitmentAttachmentService
     */
    public const CANDIDATE_SERVICE = 'recruitment.candidate_service';

    ///////////////////////////////////////////////////////////////
    /// Performance plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Performance\Service\KpiService
     */
    public const KPI_SERVICE = 'performance.kpi_service';

    /**
     * @see \ShantsHRM\Performance\Service\PerformanceTrackerService
     */
    public const PERFORMANCE_TRACKER_SERVICE = 'performance.performance_tracker_service';

    /**
     * @see \ShantsHRM\Performance\Service\PerformanceReviewService
     */
    public const PERFORMANCE_REVIEW_SERVICE = 'performance.performance_review_service';

    /**
     * @see \ShantsHRM\Performance\Service\PerformanceTrackerLogService
     */
    public const PERFORMANCE_TRACKER_LOG_SERVICE = 'performance.performance_tracker_log_service';

    ///////////////////////////////////////////////////////////////
    /// Dashboard plugin services
    ///////////////////////////////////////////////////////////////
    /**
     * @see \ShantsHRM\Dashboard\Service\EmployeeOnLeaveService
     */
    public const EMPLOYEE_ON_LEAVE_SERVICE = 'dashboard.employee_on_leave_service';

    /**
     * @see \ShantsHRM\Dashboard\Service\ChartService
     */
    public const CHART_SERVICE = 'dashboard.chart_service';

    /**
     * @see \ShantsHRM\Dashboard\Service\QuickLaunchService
     */
    public const QUICK_LAUNCH_SERVICE = 'dashboard.quick_launch_service';

    /**
     * @see \ShantsHRM\Dashboard\Service\EmployeeTimeAtWorkService
     */
    public const EMPLOYEE_TIME_AT_WORK_SERVICE = 'dashboard.employee_time_at_work_service';

    /**
     * @see \ShantsHRM\Dashboard\Service\EmployeeActionSummaryService
     */
    public const EMPLOYEE_ACTION_SUMMARY_SERVICE = 'dashboard.employee_action_summary_service';

    ///////////////////////////////////////////////////////////////
    /// LDAP plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Framework\Logger\Logger
     */
    public const LDAP_LOGGER = 'ldap.logger';

    ///////////////////////////////////////////////////////////////
    /// Buzz plugin services
    ///////////////////////////////////////////////////////////////

    /**
     * @see \ShantsHRM\Buzz\Service\BuzzAnniversaryService
     */
    public const BUZZ_ANNIVERSARY_SERVICE = 'buzz.buzz_anniversary_service';

    /**
     * @see \ShantsHRM\Buzz\Service\BuzzService
     */
    public const BUZZ_SERVICE = 'buzz.buzz_service';
}
