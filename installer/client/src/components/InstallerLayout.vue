<!--
/**
 * ShantsHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 Shants Tech LLC., http://www.hrm.shants-tech.com
 *
 * ShantsHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * ShantsHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
 -->
<template>
  <div class="shantshrm-installer">
    <div class="shantshrm-installer-sidebar">
      <installer-steps
        :steps="steps"
        :current-step="currentStep"
      ></installer-steps>
      <div class="shantshrm-installer-footer">
        <a
          v-show="currentStep === 0"
          target="_blank"
          class="shantshrm-installer-guide"
          href="https://starterhelp.hrm.shants-tech.com/hc/en-us/articles/5295915003666-ShantsHRM-Starter-Installation-Guide"
        >
          <oxd-icon
            type="svg"
            name="guide"
            class="shantshrm-installer-guide-icon"
          />
          Installation Guide
        </a>
        <br />
        <slot name="footer"></slot>
      </div>
    </div>
    <div class="shantshrm-installer-header">
      <img :src="brandingSrc" width="280" alt="shantshrm-branding" />
    </div>
    <div class="shantshrm-installer-content">
      <slot></slot>
    </div>
  </div>
</template>

<script>
import InstallerSteps from '@/components/InstallerSteps.vue';
import Icon from '@ohrm/oxd/core/components/Icon/Icon.vue';

export default {
  name: 'InstallerLayout',
  components: {
    'installer-steps': InstallerSteps,
    'oxd-icon': Icon,
  },
  props: {
    steps: {
      type: Array,
      required: true,
    },
    currentStep: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      brandingSrc: `${window.appGlobal.baseUrl}/../../web/images/ohrm_branding.png`,
    };
  },
};
</script>

<style src="./installer-layout.scss" lang="scss" scoped></style>
