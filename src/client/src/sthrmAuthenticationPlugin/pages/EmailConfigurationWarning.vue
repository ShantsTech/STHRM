<!--
  - Shants Tech is a comprehensive Human Resource Management (HRM) System that captures
  - all the essential functionalities required for any enterprise.
  - Copyright (C) 2006 Shants Tech Inc., http://www.shants-tech.com
  -
  - Shants Tech is free software; you can redistribute it and/or modify it under the terms of
  - the GNU General Public License as published by the Free Software Foundation; either
  - version 2 of the License, or (at your option) any later version.
  -
  - Shants Tech is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
  - without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
  - See the GNU General Public License for more details.
  -
  - You should have received a copy of the GNU General Public License along with this program;
  - if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
  - Boston, MA  02110-1301, USA
  -->
<template>
  <div class="sthrm-forgot-password-container">
    <div class="sthrm-forgot-password-wrapper">
      <div class="sthrm-card-container">
        <oxd-text tag="h6" class="sthrm-forgot-password-title">
          {{ $t('auth.reset_password') }}
        </oxd-text>
        <oxd-divider />
        <card-note
          :note-text="$t('auth.configured_receive_email_notification_note')"
          class="sthrm-forgot-password-card-note"
          has-note-icon
        />
        <oxd-text class="sthrm-forgot-password-reset">
          <oxd-text
            tag="span"
            class="sthrm-forgot-password-reset--link"
            @click="navigateUrl"
          >
            {{ $t('general.click_here') }}
          </oxd-text>
          {{ $t('auth.back_to_login') }}
        </oxd-text>
      </div>
    </div>
    <slot name="footer"></slot>
  </div>
</template>

<script>
import CardNote from '../components/CardNote';
import {navigate} from '@ohrm/core/util/helper/navigation';
export default {
  name: 'EmailConfigurationWarning',
  components: {
    'card-note': CardNote,
  },
  methods: {
    navigateUrl() {
      navigate('/auth/login');
    },
  },
};
</script>
<style src="./reset-password.scss" lang="scss" scoped></style>
