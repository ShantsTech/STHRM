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
        <oxd-text type="card-title">{{ $t('general.are_you_sure') }}</oxd-text>
      </div>
      <div class="sthrm-text-center-align">
        <oxd-text type="card-body">
          {{ message || $t('general.delete_confirmation_message') }}
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
          :label="$t('general.yes_delete')"
          icon-name="trash"
          display-type="label-danger"
          class="sthrm-button-margin"
          @click="onDelete"
        />
      </div>
    </oxd-dialog>
  </teleport>
</template>

<script>
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';

export default {
  components: {
    'oxd-dialog': Dialog,
  },
  props: {
    message: {
      type: String,
      default: null,
      required: false,
    },
  },
  data() {
    return {
      show: false,
      reject: null,
      resolve: null,
    };
  },
  methods: {
    showDialog() {
      return new Promise((resolve, reject) => {
        this.resolve = resolve;
        this.reject = reject;
        this.show = true;
      });
    },
    onDelete() {
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

<style src="./dialog.scss" lang="scss" scoped></style>
