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
  <oxd-input-field
    type="select"
    :label="$t('pim.include')"
    :options="includeOpts"
    :show-empty-selector="false"
  />
</template>

<script>
export default {
  name: 'IncludeEmployeeDropdown',
  data() {
    return {
      includeOpts: [
        {
          id: 1,
          param: 'onlyCurrent',
          label: this.$t('general.current_employees_only'),
        },
        {
          id: 2,
          param: 'currentAndPast',
          label: this.$t('general.current_and_past_employees'),
        },
        {
          id: 3,
          param: 'onlyPast',
          label: this.$t('general.past_employees_only'),
        },
      ],
    };
  },
};
</script>
