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
  <oxd-dialog class="sthrm-dialog-popup" @update:show="onCancel">
    <div class="sthrm-modal-header">
      <oxd-text type="card-title">
        {{ $t('leave.situational_leave') }}
      </oxd-text>
    </div>
    <div class="sthrm-text-center-align">
      <oxd-text type="card-body">
        {{ $t('leave.entitlement_situational_description') }}
      </oxd-text>
    </div>
    <div class="sthrm-modal-footer">
      <oxd-form-actions>
        <oxd-button
          type="submit"
          display-type="secondary"
          :label="$t('general.ok')"
          @click="onCancel"
        />
      </oxd-form-actions>
    </div>
  </oxd-dialog>
</template>

<script>
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';

export default {
  name: 'EntitlementSituationalModal',
  components: {
    'oxd-dialog': Dialog,
  },
  emits: ['close'],
  methods: {
    onCancel() {
      this.$emit('close', true);
    },
  },
};
</script>

<style lang="scss" scoped>
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

.sthrm-text-center-align {
  text-align: center;
}
</style>
