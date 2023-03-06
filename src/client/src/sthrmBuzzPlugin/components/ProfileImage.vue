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
  <div class="sthrm-buzz-profile-image">
    <img alt="profile picture" :src="imageSrc" />
  </div>
</template>

<script>
export default {
  name: 'ProfileImage',
  props: {
    employee: {
      type: Object,
      required: true,
    },
  },
  computed: {
    imageSrc() {
      return `../pim/viewPhoto/empNumber/${this.employee.empNumber}`;
    },
  },
};
</script>

<style lang="scss" scoped>
.sthrm-buzz-profile-image {
  & img {
    width: 45px;
    height: 45px;
    display: flex;
    flex-shrink: 0;
    border-radius: 100%;
    box-sizing: border-box;
    justify-content: center;
  }
}
</style>
