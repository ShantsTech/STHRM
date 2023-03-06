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
  <oxd-text tag="p" class="sthrm-form-hint">
    * {{ $t('general.required') }}
  </oxd-text>
</template>

<script>
export default {
  name: 'RequiredText',
};
</script>

<style lang="scss" scoped>
.sthrm-form-hint {
  margin-right: auto;
  font-weight: 600;
  font-size: 0.75rem;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}
</style>
