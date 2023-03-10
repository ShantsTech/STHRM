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
  <oxd-dialog
    v-if="show"
    :gutters="false"
    class="sthrm-dialog-modal"
    @update:show="onCancel"
  >
    <div class="sthrm-dialog-header-container">
      <oxd-text type="card-title">
        {{ $t('leave.updating_entitlement') }} -
        {{ $t('leave.matching_employees') }}
      </oxd-text>
    </div>
    <oxd-divider
      class="sthrm-dialog-horizontal-margin sthrm-clear-margins"
    />
    <div
      class="sthrm-dialog-horizontal-padding sthrm-dialog-vertical-padding"
    >
      <oxd-text type="subtitle-2">
        {{
          $t('leave.selected_leave_entitlement_applied_to_following_employees')
        }}
      </oxd-text>
    </div>
    <div class="sthrm-container">
      <oxd-card-table
        :headers="headers"
        :items="items"
        :clickable="false"
        class="sthrm-horizontal-padding"
        row-decorator="oxd-table-decorator-card"
      />
    </div>
    <div
      class="sthrm-dialog-horizontal-padding sthrm-dialog-vertical-padding"
    >
      <oxd-form-actions>
        <oxd-button
          display-type="ghost"
          :label="$t('general.cancel')"
          @click="onCancel"
        />
        <submit-button :label="$t('general.confirm')" @click="onConfirm" />
      </oxd-form-actions>
    </div>
  </oxd-dialog>
</template>

<script>
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';
import {APIService} from '@ohrm/core/util/services/api.service';

export default {
  name: 'EntitlementBulkUpdateModal',
  components: {
    'oxd-dialog': Dialog,
  },
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  setup() {
    const http = new APIService(
      window.appGlobal.baseUrl,
      '/api/v2/leave/employees/leave-entitlements',
    );
    return {
      http,
    };
  },
  data() {
    return {
      show: false,
      reject: null,
      resolve: null,
      headers: [
        {
          title: this.$t('general.employee'),
          name: 'employee',
          slot: 'title',
          style: {flex: 1},
        },
        {
          title: this.$t('leave.old_entitlement'),
          name: 'current',
          style: {flex: 1},
        },
        {
          title: this.$t('leave.new_entitlement'),
          name: 'updateAs',
          style: {flex: 1},
        },
      ],
      items: [],
    };
  },
  methods: {
    showDialog() {
      return this.http
        .getAll({
          leaveTypeId: this.data.leaveType?.id,
          fromDate: this.data.leavePeriod?.startDate,
          toDate: this.data.leavePeriod?.endDate,
          entitlement: this.data.entitlement,
          locationId: this.data.location?.id,
          subunitId: this.data.subunit?.id,
        })
        .then(response => {
          const {data} = response.data;
          this.items = Array.isArray(data)
            ? data.map(item => {
                return {
                  employee: `${item.firstName} ${item.lastName}`,
                  current: item.entitlement?.current
                    ? parseFloat(item.entitlement.current).toFixed(2)
                    : '0.00',
                  updateAs: item.entitlement?.updateAs
                    ? parseFloat(item.entitlement.updateAs).toFixed(2)
                    : '0.00',
                };
              })
            : [];
          return new Promise((resolve, reject) => {
            this.resolve = resolve;
            this.reject = reject;
            this.show = true;
          });
        });
    },
    onConfirm() {
      this.show = false;
      this.resolve && this.resolve('ok');
    },
    onCancel() {
      this.show = false;
      this.resolve && this.resolve('cancel');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@ohrm/oxd/styles/_mixins.scss';

.sthrm-container {
  max-height: 165px;
  overflow-y: auto;
  @include oxd-scrollbar();
}
</style>
