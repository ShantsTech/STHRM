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
  <div class="sthrm-action-header">
    <oxd-text tag="h6" class="sthrm-main-title">
      <slot></slot>
    </oxd-text>
    <oxd-button
      :label="$t('general.add')"
      icon-name="plus"
      display-type="text"
      v-bind="$attrs"
    />
  </div>
</template>

<script>
export default {
  name: 'InlineActionButton',
  inheritAttrs: false,
};
</script>

<style lang="scss" scoped>
.sthrm-action {
  &-header {
    display: flex;
    overflow-wrap: break-word;
    align-items: center;
    button {
      margin-left: 1rem;
      white-space: nowrap;
    }
  }
}
</style>
