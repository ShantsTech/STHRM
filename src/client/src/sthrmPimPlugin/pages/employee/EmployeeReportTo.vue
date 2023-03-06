<!--
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
 -->

<template>
  <edit-employee-layout :employee-id="empNumber" screen="report-to">
    <div class="sthrm-horizontal-padding sthrm-top-padding">
      <oxd-text tag="h6" class="sthrm-main-title">{{
        $t('pim.report_to')
      }}</oxd-text>
      <oxd-divider />
    </div>
    <employee-supervisors
      :emp-number="empNumber"
      :reporting-methods="reportingMethods"
    ></employee-supervisors>
    <employee-subordinates
      :emp-number="empNumber"
      :reporting-methods="reportingMethods"
    ></employee-subordinates>
  </edit-employee-layout>
</template>

<script>
import EditEmployeeLayout from '@/sthrmPimPlugin/components/EditEmployeeLayout';
import EmployeeSupervisors from '@/sthrmPimPlugin/components/EmployeeSupervisors';
import EmployeeSubordinates from '@/sthrmPimPlugin/components/EmployeeSubordinates';

export default {
  components: {
    'edit-employee-layout': EditEmployeeLayout,
    'employee-supervisors': EmployeeSupervisors,
    'employee-subordinates': EmployeeSubordinates,
  },
  props: {
    empNumber: {
      type: String,
      required: true,
    },
    reportingMethods: {
      type: Array,
      required: true,
    },
  },
};
</script>
