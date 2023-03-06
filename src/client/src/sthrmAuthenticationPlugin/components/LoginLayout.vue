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
  <div class="sthrm-login-layout">
    <div class="sthrm-login-layout-blob">
      <div class="sthrm-login-container">
        <div class="sthrm-login-slot-wrapper">
          <login-branding :img-src="loginBannerSrc"></login-branding>
          <div class="sthrm-login-slot">
            <div class="sthrm-login-logo-mobile">
              <img :src="loginLogoSrc" alt="sthrm-logo" />
            </div>
            <slot></slot>
          </div>
        </div>
      </div>
      <div class="sthrm-login-logo">
        <img :src="loginLogoSrc" alt="sthrm-logo" />
      </div>
    </div>
  </div>
</template>

<script>
import LoginBranding from './LoginBranding';

export default {
  name: 'LoginLayout',
  components: {
    'login-branding': LoginBranding,
  },
  props: {
    loginLogoSrc: {
      type: String,
      required: true,
    },
    loginBannerSrc: {
      type: String,
      required: true,
    },
  },
};
</script>

<style src="./login-layout.scss" lang="scss" scoped></style>
