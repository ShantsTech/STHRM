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
  <div class="sthrm-recruitment-interviewer-input">
    <employee-autocomplete
      :label="!showDelete ? $t('recruitment.interviewer') : null"
      :disabled="disabled"
      v-bind="$attrs"
      api-path="/api/v2/recruitment/interviewers"
    />
    <oxd-icon-button
      v-if="showDelete && !disabled"
      name="trash-fill"
      class="sthrm-recruitment-delete-icon"
      :with-container="false"
      @click="remove"
    />
  </div>
</template>

<script>
import EmployeeAutocomplete from '@/core/components/inputs/EmployeeAutocomplete.vue';

export default {
  name: 'InterviewerAutocomplete',
  components: {
    'employee-autocomplete': EmployeeAutocomplete,
  },
  inheritAttrs: false,
  props: {
    showDelete: {
      type: Boolean,
      required: true,
    },
    includeEmployee: {
      type: String,
      default: 'currentAndPast',
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  emits: ['remove'],
  methods: {
    remove() {
      this.$emit('remove');
    },
  },
};
</script>

<style lang="scss" scoped>
.sthrm-recruitment {
  &-interviewer-input {
    display: flex;
    align-items: flex-start;
    ::v-deep(.oxd-input-group__label-wrapper:empty) {
      display: none;
    }
  }
  &-delete-icon {
    margin-left: 1rem;
    margin-top: 1rem;
  }
}
</style>
