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
  <div class="sthrm-label-link-wrapper">
    <oxd-text class="sthrm-text-label" tag="p">{{ label }} : </oxd-text>
    <a :href="url" target="_blank" class="sthrm-vacancy-link">
      {{ url }}
    </a>
  </div>
</template>

<script>
export default {
  name: 'VacancyLinkCard',
  props: {
    label: {
      type: String,
      required: true,
    },
    url: {
      type: String,
      required: true,
    },
  },
};
</script>

<style lang="scss" scoped>
.sthrm-label-link-wrapper {
  display: flex;
  flex-wrap: wrap;
  &:first-of-type {
    margin-bottom: 0.25rem;
  }
}
.sthrm-text-label {
  font-size: 12px;
  font-weight: 600;
  padding-right: 0.2rem;
  color: $oxd-interface-gray-darken-1-color;
}
.sthrm-vacancy-link {
  font-size: 12px;
  font-weight: 600;
  margin-left: 1rem;
  text-decoration: none;
  word-break: break-all;
  font-family: $oxd-font-family;
  color: $oxd-primary-one-color;
}
</style>
