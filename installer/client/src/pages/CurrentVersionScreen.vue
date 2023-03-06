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
  <oxd-form
    class="shantshrm-installer-page"
    :loading="isLoading"
    @submit-valid="onSubmit"
  >
    <oxd-text tag="h5" class="shantshrm-installer-page-title">
      Current Version Details
    </oxd-text>
    <br />
    <oxd-text class="shantshrm-installer-page-content">
      Select your current ShantsHRM version here. You can find the version at
      the bottom of the ShantsHRM login page. ShantsHRM Upgrader only supports
      versions listed in the dropdown. Selecting a different version would lead
      to an upgrade failure and a database corruption.
    </oxd-text>
    <br />

    <oxd-form-row>
      <oxd-grid :cols="3" class="shantshrm-full-width-grid">
        <oxd-grid-item>
          <version-dropdown
            v-model="version"
            :rules="rules.version"
            required
          ></version-dropdown>
        </oxd-grid-item>
      </oxd-grid>
    </oxd-form-row>

    <oxd-text tag="p" class="shantshrm-installer-page-content">
      Click <b>Next</b> to commence upgrading your instance
    </oxd-text>

    <oxd-form-actions class="shantshrm-installer-page-action">
      <required-text />
      <oxd-button display-type="ghost" label="Back" @click="onClickBack" />
      <oxd-button
        class="shantshrm-left-space"
        display-type="secondary"
        label="Next"
        type="submit"
      />
    </oxd-form-actions>
  </oxd-form>
</template>

<script>
import {APIService} from '@/core/util/services/api.service';
import {required} from '@/core/util/validation/rules';
import {navigate} from '@/core/util/helper/navigation.ts';
import VersionDropdown from '@/components/VersionDropdown.vue';

export default {
  name: 'CurrentVersionScreen',
  components: {
    'version-dropdown': VersionDropdown,
  },
  setup() {
    const http = new APIService(window.appGlobal.baseUrl, '');
    return {
      http,
    };
  },
  data() {
    return {
      isLoading: false,
      version: null,
      rules: {
        version: [required],
      },
    };
  },
  beforeMount() {
    this.isLoading = true;
    this.http
      .request({
        method: 'GET',
        url: '/upgrader/api/current-version',
      })
      .then((response) => {
        const {version} = response.data;
        if (version) {
          this.version = {
            id: version,
            label: version,
          };
        }
        this.isLoading = false;
      });
  },
  methods: {
    onClickBack() {
      navigate('/upgrader/system-check');
    },
    onSubmit() {
      this.isLoading = true;
      this.http
        .request({
          method: 'POST',
          url: '/upgrader/api/current-version',
          data: {
            currentVersion: this.version?.id,
          },
        })
        .then(() => {
          return this.http.request({
            method: 'POST',
            url: '/upgrader/api/send-data/upgrader-start',
          });
        })
        .then(() => {
          navigate('/upgrader/process');
        });
    },
  },
};
</script>
<style src="./installer-page.scss" lang="scss" scoped></style>
