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
  <div v-if="!loading">
    <oxd-divider v-if="showDivider" class="sthrm-horizontal-margin" />
    <div class="sthrm-horizontal-padding sthrm-vertical-padding">
      <div v-if="selected > 0">
        <oxd-text tag="span">
          {{ $t('general.n_records_selected', {count: selected}) }}
        </oxd-text>
        <oxd-button
          :label="$t('general.delete_selected')"
          icon-name="trash-fill"
          display-type="label-danger"
          class="sthrm-horizontal-margin"
          @click="$emit('delete', $event)"
        />
      </div>
      <oxd-text v-else tag="span">
        {{ $t('general.n_records_found', {count: total}) }}
      </oxd-text>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TableHeader',

  props: {
    loading: {
      type: Boolean,
      required: true,
    },
    selected: {
      type: Number,
      required: true,
    },
    total: {
      type: Number,
      required: true,
    },
    showDivider: {
      type: Boolean,
      required: false,
      default: true,
    },
  },

  emits: ['delete'],
};
</script>

<style scoped>
.oxd-divider {
  margin-top: 0;
  margin-bottom: 0;
}
</style>
