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
  <teleport to="#app">
    <oxd-dialog
      v-if="show"
      :style="{maxWidth: '450px'}"
      @update:show="onCancel"
    >
      <div class="sthrm-modal-header">
        <oxd-text type="card-title">
          {{ $t('leave.updating_entitlement') }}
        </oxd-text>
      </div>
      <div class="sthrm-text-center-align">
        <oxd-text type="card-body">
          {{
            $t('leave.entitlement_value_confirmation_message', {
              oldvalue: current,
              newvalue: updateAs,
            })
          }}
        </oxd-text>
      </div>
      <div class="sthrm-modal-footer">
        <oxd-button
          display-type="ghost"
          class="sthrm-button-margin"
          :label="$t('general.cancel')"
          @click="onCancel"
        />
        <oxd-button
          display-type="secondary"
          class="sthrm-button-margin"
          :label="$t('general.confirm')"
          @click="onConfirm"
        />
      </div>
    </oxd-dialog>
  </teleport>
</template>

<script>
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';
import {APIService} from '@ohrm/core/util/services/api.service';

export default {
  name: 'EntitlementUpdateModal',
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
    const http = new APIService(window.appGlobal.baseUrl, '');
    return {
      http,
    };
  },
  data() {
    return {
      show: false,
      reject: null,
      resolve: null,
      current: '0.00',
      updateAs: '0.00',
    };
  },
  methods: {
    showDialog() {
      return this.http
        .request({
          method: 'GET',
          url: `api/v2/leave/employees/${this.data.employee?.id}/leave-entitlements`,
          params: {
            leaveTypeId: this.data.leaveType?.id,
            fromDate: this.data.leavePeriod?.startDate,
            toDate: this.data.leavePeriod?.endDate,
            entitlement: this.data.entitlement,
          },
        })
        .then(response => {
          const {data} = response.data;
          this.current = data.entitlement?.current
            ? parseFloat(data.entitlement.current).toFixed(2)
            : '0.00';
          this.updateAs = data.entitlement?.updateAs
            ? parseFloat(data.entitlement.updateAs).toFixed(2)
            : '0.00';
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

<style scoped>
.sthrm-modal-header {
  margin-bottom: 1.2rem;
  display: flex;
  justify-content: center;
}
.sthrm-modal-footer {
  margin-top: 1.2rem;
  display: flex;
  justify-content: center;
}
.sthrm-button-margin {
  margin: 0.25rem;
}
.sthrm-text-center-align {
  text-align: center;
}
</style>
