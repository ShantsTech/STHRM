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
  <div class="sthrm-card-container">
    <oxd-text tag="h6" class="sthrm-main-title">
      {{ titleLabel }}
    </oxd-text>
    <oxd-divider />
    <oxd-form @submit="emitEmployee">
      <oxd-form-row>
        <oxd-grid :cols="3" class="sthrm-full-width-grid">
          <oxd-grid-item>
            <employee-autocomplete
              v-model="employee"
              :rules="rules.employee"
              :params="{includeEmployees: includeEmployeesParam}"
              :label="autocompleteLabel"
              required
            />
          </oxd-grid-item>
        </oxd-grid>
      </oxd-form-row>
      <oxd-divider />
      <oxd-form-actions>
        <required-text />
        <oxd-button
          display-type="secondary"
          :label="$t('general.search')"
          type="submit"
        />
      </oxd-form-actions>
    </oxd-form>
  </div>
</template>

<script>
import {required, validSelection} from '@/core/util/validation/rules';
import EmployeeAutocomplete from '@/core/components/inputs/EmployeeAutocomplete';
import RequiredText from '@/core/components/labels/RequiredText';

export default {
  name: 'EmployeeRecords',

  components: {
    'required-text': RequiredText,
    'employee-autocomplete': EmployeeAutocomplete,
  },

  props: {
    includeEmployeesParam: {
      type: String,
      required: true,
    },
    titleLabel: {
      type: String,
      required: true,
    },
    autocompleteLabel: {
      type: String,
      required: true,
    },
  },

  emits: ['search'],

  data() {
    return {
      employee: null,
      rules: {
        employee: [required, validSelection],
      },
    };
  },

  methods: {
    emitEmployee() {
      this.$emit('search', this.employee?._employee);
    },
  },
};
</script>
