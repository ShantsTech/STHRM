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
  <oxd-dialog class="sthrm-dialog-popup" @update:show="onClose">
    <div class="sthrm-modal-header">
      <oxd-text type="card-title">{{ $t('pim.import_details') }}</oxd-text>
    </div>
    <div class="sthrm-text-center-align">
      <oxd-text
        type="card-body"
        :class="{
          'sthrm-success-message': data.success > 0,
        }"
      >
        {{ $t('pim.n_records_successfully_imported', {count: data.success}) }}
      </oxd-text>
      <template v-if="data.failed > 0">
        <oxd-text type="card-body" class="sthrm-error-message">
          {{ $t('pim.n_records_failed_to_import', {count: data.failed}) }}
        </oxd-text>
        <oxd-text type="card-body" class="sthrm-error-message">
          {{ $t('pim.failed_rows') }}
        </oxd-text>
        <oxd-text type="card-body" class="sthrm-error-message">
          {{ data.failedRows.toString() }}
        </oxd-text>
      </template>
    </div>
    <div class="sthrm-modal-footer">
      <oxd-button
        display-type="text"
        :label="$t('general.ok')"
        @click="onClose"
      />
    </div>
  </oxd-dialog>
</template>

<script>
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';

export default {
  name: 'EmployeeDataImportModal',
  components: {
    'oxd-dialog': Dialog,
  },
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  emits: ['close'],
  methods: {
    onClose() {
      this.$emit('close', true);
    },
  },
};
</script>

<style lang="scss" scoped>
.sthrm-modal-header {
  display: flex;
  margin-bottom: 1.2rem;
  justify-content: center;
}
.sthrm-modal-footer {
  display: flex;
  margin-top: 1.2rem;
  justify-content: center;
}
.sthrm-text-center-align {
  text-align: center;
  overflow-wrap: break-word;
}
::v-deep(.sthrm-dialog-popup) {
  width: 450px;
}
.sthrm-success-message {
  color: $oxd-feedback-success-color;
}
.sthrm-error-message {
  color: $oxd-feedback-danger-color;
}
</style>
