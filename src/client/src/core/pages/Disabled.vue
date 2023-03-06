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
  <div class="sthrm-background-container">
    <div class="sthrm-card-container">
      <div class="sthrm-module-disabled">
        <oxd-text tag="h1" class="sthrm-module-disabled-heading">
          403
        </oxd-text>
        <oxd-text tag="h4" class="sthrm-module-disabled-sub-heading">
          {{ $t('general.module_forbidden') }}
        </oxd-text>
        <oxd-text tag="h6">
          {{ $t('general.module_access_restriction') }}
        </oxd-text>
      </div>
    </div>
  </div>
</template>

<style lang="scss" scoped>
.sthrm-module-disabled {
  text-align: center;
  margin-bottom: 1.2rem;
  &-heading {
    line-height: normal;
    color: $oxd-primary-one-color;
    font-weight: 800;
  }
  &-sub-heading {
    margin-top: -1.5rem;
    padding-bottom: 1rem;
    color: $oxd-primary-one-color;
    font-weight: 600;
  }
}
</style>
