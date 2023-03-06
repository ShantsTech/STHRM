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
          {{ $t('general.no_matching_employees') }}
        </oxd-text>
      </div>
      <div class="sthrm-text-center-align">
        <oxd-text type="card-body">
          {{ $t('general.no_employees_match_filters') }}
        </oxd-text>
      </div>
      <div class="sthrm-modal-footer">
        <oxd-button
          display-type="secondary"
          :label="$t('general.ok')"
          @click="onCancel"
        />
      </div>
    </oxd-dialog>
  </teleport>
</template>

<script>
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';

export default {
  name: 'EntitlementNoMatchModal',
  components: {
    'oxd-dialog': Dialog,
  },
  data() {
    return {
      show: false,
    };
  },
  methods: {
    showDialog() {
      this.show = true;
    },
    onCancel() {
      this.show = false;
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
