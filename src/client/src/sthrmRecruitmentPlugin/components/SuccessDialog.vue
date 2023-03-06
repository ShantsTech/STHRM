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
    <simple-dialog
      v-if="show"
      :with-close="false"
      class="sthrm-confirmation-dialog sthrm-dialog-popup"
      @update:show="onSuccess"
    >
      <div class="sthrm-modal-header">
        <oxd-text type="card-title">
          {{ $t('recruitment.application_received') }}
        </oxd-text>
      </div>
      <div class="sthrm-text-center-align">
        <oxd-text type="card-body">
          {{
            $t('recruitment.your_application_has_been_submitted_successfully')
          }}
        </oxd-text>
      </div>
      <div class="sthrm-modal-footer">
        <oxd-button
          :label="$t('general.ok')"
          display-type="text"
          class="sthrm-button-margin"
          @click="onSuccess"
        />
      </div>
    </simple-dialog>
  </teleport>
</template>

<script>
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';

export default {
  name: 'SuccessDialog',
  components: {
    'simple-dialog': Dialog,
  },
  data() {
    return {
      show: false,
      resolve: null,
    };
  },
  methods: {
    showSuccessDialog() {
      return new Promise(resolve => {
        this.resolve = resolve;
        this.show = true;
      });
    },
    onSuccess() {
      this.show = false;
      this.resolve && this.resolve('ok');
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

.sthrm-button-margin {
  margin: 0.25rem;
}

.sthrm-text-center-align {
  text-align: center;
}
</style>
