/**
 * Shants Tech is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 Shants Tech Inc., http://www.shants-tech.com
 *
 * Shants Tech is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * Shants Tech is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

import CorePages from '@/core/pages';
import AdminPages from '@/sthrmAdminPlugin';
import PimPages from '@/sthrmPimPlugin';
import HelpPages from '@/sthrmHelpPlugin';
import TimePages from '@/sthrmTimePlugin';
import LeavePages from '@/sthrmLeavePlugin';
import OAuthPages from '@/sthrmCoreOAuthPlugin';
import AttendancePages from '@/sthrmAttendancePlugin';
import MaintenancePages from '@/sthrmMaintenancePlugin';
import RecruitmentPages from '@/sthrmRecruitmentPlugin';
import PerformancePages from '@/sthrmPerformancePlugin';
import CorporateDirectoryPages from '@/sthrmCorporateDirectoryPlugin';
import authenticationPages from '@/sthrmAuthenticationPlugin';
import languagePages from '@/sthrmAdminPlugin';
import dashboardPages from '@/sthrmDashboardPlugin';
import buzzPages from '@/sthrmBuzzPlugin';
import systemCheckPages from '@/sthrmSystemCheckPlugin';

export default {
  ...AdminPages,
  ...PimPages,
  ...CorePages,
  ...HelpPages,
  ...TimePages,
  ...OAuthPages,
  ...LeavePages,
  ...AttendancePages,
  ...MaintenancePages,
  ...RecruitmentPages,
  ...PerformancePages,
  ...CorporateDirectoryPages,
  ...authenticationPages,
  ...languagePages,
  ...dashboardPages,
  ...buzzPages,
  ...systemCheckPages,
};
