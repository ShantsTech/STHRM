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
  <login-layout>
    <oxd-text class="sthrm-login-title" tag="h5">
      {{ $t('auth.login') }}
    </oxd-text>
    <div class="sthrm-login-form">
      <div class="sthrm-login-error">
        <oxd-alert
          :show="error !== null"
          :message="error?.message || ''"
          type="error"
        ></oxd-alert>
        <oxd-sheet
          v-if="isDemoMode"
          type="gray-lighten-2"
          class="sthrm-demo-credentials"
        >
          <oxd-text tag="p">Username : Admin</oxd-text>
          <oxd-text tag="p">Password : admin123</oxd-text>
        </oxd-sheet>
      </div>
      <oxd-form
        ref="loginForm"
        method="post"
        :action="submitUrl"
        @submitValid="onSubmit"
      >
        <input name="_token" :value="token" type="hidden" />

        <oxd-form-row>
          <oxd-input-field
            v-model="username"
            name="username"
            :label="$t('general.username')"
            label-icon="person"
            :placeholder="$t('auth.username')"
            :rules="rules.username"
            autofocus
          />
        </oxd-form-row>

        <oxd-form-row>
          <oxd-input-field
            v-model="password"
            name="password"
            :label="$t('general.password')"
            label-icon="key"
            :placeholder="$t('auth.password')"
            type="password"
            :rules="rules.password"
          />
        </oxd-form-row>

        <oxd-form-actions class="sthrm-login-action">
          <oxd-button
            class="sthrm-login-button"
            display-type="main"
            :label="$t('auth.login')"
            type="submit"
          />
        </oxd-form-actions>
        <div class="sthrm-login-forgot">
          <oxd-text class="sthrm-login-forgot-header" @click="navigateUrl">
            {{ $t('auth.forgot_password') }}?
          </oxd-text>
        </div>
      </oxd-form>
      <br />
    </div>
    <div class="sthrm-login-footer">
      <div v-if="showSocialMedia" class="sthrm-login-footer-sm">
        <a
          href="https://www.linkedin.com/company/sthrm/mycompany/"
          target="_blank"
        >
          <oxd-icon type="svg" class="sthrm-sm-icon" name="linkedinFill" />
        </a>
        <a href="https://www.facebook.com/Shants Tech/" target="_blank">
          <oxd-icon type="svg" class="sthrm-sm-icon" name="facebookFill" />
        </a>
        <a href="https://twitter.com/sthrm?lang=en" target="_blank">
          <oxd-icon type="svg" class="sthrm-sm-icon" name="twitterFill" />
        </a>
        <a href="https://www.youtube.com/c/Shants TechInc" target="_blank">
          <oxd-icon type="svg" class="sthrm-sm-icon" name="youtubeFill" />
        </a>
      </div>
      <slot name="footer"></slot>
    </div>
  </login-layout>
</template>

<script>
import {urlFor} from '@ohrm/core/util/helper/url';
import {required} from '@ohrm/core/util/validation/rules';
import {navigate, reloadPage} from '@ohrm/core/util/helper/navigation';
import LoginLayout from '../components/LoginLayout';
import Alert from '@ohrm/oxd/core/components/Alert/Alert';
import Icon from '@ohrm/oxd/core/components/Icon/Icon.vue';
import Sheet from '@ohrm/oxd/core/components/Sheet/Sheet';

export default {
  components: {
    'oxd-icon': Icon,
    'oxd-alert': Alert,
    'oxd-sheet': Sheet,
    'login-layout': LoginLayout,
  },

  props: {
    error: {
      type: Object,
      default: () => null,
    },
    token: {
      type: String,
      required: true,
    },
    showSocialMedia: {
      type: Boolean,
      default: true,
    },
    isDemoMode: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      username: '',
      password: '',
      rules: {
        username: [required],
        password: [required],
      },
    };
  },

  computed: {
    submitUrl() {
      return urlFor('/auth/validate');
    },
  },

  beforeMount() {
    setTimeout(() => {
      reloadPage();
    }, 1200000); // 20 * 60 * 1000 (20 minutes);
  },

  methods: {
    onSubmit() {
      this.$refs.loginForm.$el.submit();
    },
    navigateUrl() {
      navigate('/auth/requestPasswordResetCode');
    },
  },
};
</script>

<style src="./login.scss" lang="scss" scoped></style>
