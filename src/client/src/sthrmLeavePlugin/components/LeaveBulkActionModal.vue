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
      class="sthrm-dialog-popup"
      @update:show="onCancel"
    >
      <div class="sthrm-modal-header">
        <oxd-text type="card-title">
          {{ $t('leave.leave_action', {action: action}) }}
        </oxd-text>
      </div>
      <div class="sthrm-text-center-align">
        <oxd-text type="subtitle-2">
          {{
            $t('leave.bulk_leave_action_confirm_message_one', {
              action: action,
              count: count,
            })
          }}
          {{ $t('leave.bulk_leave_action_confirm_message_two') }}
        </oxd-text>
      </div>
      <div class="sthrm-modal-footer">
        <oxd-button
          :label="$t('general.no_cancel')"
          display-type="text"
          class="sthrm-button-margin"
          @click="onCancel"
        />
        <oxd-button
          :label="$t('leave.yes_confirm')"
          display-type="secondary"
          @click="onConfirm"
        />
      </div>
    </oxd-dialog>
  </teleport>
</template>

<script>
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';

export default {
  name: 'LeaveBulkActionModal',
  components: {
    'oxd-dialog': Dialog,
  },
  props: {
    data: {
      type: Object,
      default: () => null,
    },
  },
  data() {
    return {
      show: false,
      reject: null,
      resolve: null,
    };
  },
  computed: {
    count() {
      return this.data?.count ? this.data.count : 0;
    },
    action() {
      return this.data?.action;
    },
  },
  methods: {
    showDialog() {
      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
        this.show = true;
      });
    },
    onCancel() {
      this.show = false;
      this.resolve && this.resolve('cancel');
    },
    onConfirm() {
      this.show = false;
      this.resolve && this.resolve('ok');
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
