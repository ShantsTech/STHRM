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
      <oxd-text tag="h6" class="sthrm-main-title">
        {{ $t('admin.module_configuration') }}
      </oxd-text>

      <oxd-divider />

      <oxd-form :loading="isLoading" @submitValid="onSave">
        <oxd-form-row>
          <oxd-grid :cols="3" class="sthrm-full-width-grid">
            <div class="sthrm-module-field-row">
              <oxd-text tag="p" class="sthrm-module-field-label">
                {{ $t('admin.admin_module') }}
              </oxd-text>
              <oxd-switch-input v-model="modules.admin" :disabled="true" />
            </div>
            <div class="sthrm-module-field-row">
              <oxd-text tag="p" class="sthrm-module-field-label">
                {{ $t('admin.pim_module') }}
              </oxd-text>
              <oxd-switch-input v-model="modules.pim" :disabled="true" />
            </div>
            <div class="sthrm-module-field-row">
              <oxd-text tag="p" class="sthrm-module-field-label">
                {{ $t('admin.leave_module') }}
              </oxd-text>
              <oxd-switch-input v-model="modules.leave" />
            </div>
            <div class="sthrm-module-field-row">
              <oxd-text tag="p" class="sthrm-module-field-label">
                {{ $t('admin.time_module') }}
              </oxd-text>
              <oxd-switch-input v-model="modules.time" />
            </div>
            <div class="sthrm-module-field-row">
              <oxd-text tag="p" class="sthrm-module-field-label">
                {{ $t('admin.recruitment_module') }}
              </oxd-text>
              <oxd-switch-input v-model="modules.recruitment" />
            </div>
            <div class="sthrm-module-field-row">
              <oxd-text tag="p" class="sthrm-module-field-label">
                {{ $t('admin.performance_module') }}
              </oxd-text>
              <oxd-switch-input v-model="modules.performance" />
            </div>
            <div class="sthrm-module-field-row">
              <oxd-text tag="p" class="sthrm-module-field-label">
                {{ $t('admin.directory_module') }}
              </oxd-text>
              <oxd-switch-input v-model="modules.directory" />
            </div>
            <div class="sthrm-module-field-row">
              <oxd-text tag="p" class="sthrm-module-field-label">
                {{ $t('admin.maintenance_module') }}
              </oxd-text>
              <oxd-switch-input v-model="modules.maintenance" />
            </div>
            <div class="sthrm-module-field-row">
              <oxd-text tag="p" class="sthrm-module-field-label">
                {{ $t('general.mobile') }}
              </oxd-text>
              <oxd-switch-input disabled />
            </div>
          </oxd-grid>
        </oxd-form-row>

        <oxd-divider />

        <oxd-form-actions>
          <submit-button />
        </oxd-form-actions>
      </oxd-form>
    </div>
  </div>
</template>

<script>
import SwitchInput from '@ohrm/oxd/core/components/Input/SwitchInput';
import {APIService} from '@/core/util/services/api.service';
import {reloadPage} from '@/core/util/helper/navigation';

const modulesModel = {
  admin: false,
  pim: false,
  leave: false,
  time: false,
  recruitment: false,
  performance: false,
  maintenance: false,
  mobile: false,
  directory: false,
};

export default {
  components: {
    'oxd-switch-input': SwitchInput,
  },
  setup() {
    const http = new APIService(
      window.appGlobal.baseUrl,
      'api/v2/admin/modules',
    );
    return {
      http,
    };
  },
  data() {
    return {
      modules: {...modulesModel},
      isLoading: false,
    };
  },
  created() {
    this.isLoading = true;
    this.http
      .getAll()
      .then(response => {
        const {data} = response.data;
        this.modules.admin = data.admin;
        this.modules.pim = data.pim;
        this.modules.leave = data.leave;
        this.modules.time = data.time;
        this.modules.recruitment = data.recruitment;
        this.modules.performance = data.performance;
        this.modules.maintenance = data.maintenance;
        this.modules.mobile = data.mobile;
        this.modules.directory = data.directory;
      })
      .finally(() => {
        this.isLoading = false;
      });
  },
  methods: {
    onSave() {
      this.isLoading = true;
      const payload = {
        admin: true,
        pim: true,
        leave: this.modules.leave,
        time: this.modules.time,
        recruitment: this.modules.recruitment,
        performance: this.modules.performance,
        maintenance: this.modules.maintenance,
        mobile: this.modules.mobile,
        directory: this.modules.directory,
      };
      this.http
        .request({
          method: 'PUT',
          data: payload,
        })
        .then(response => {
          const {data} = response.data;
          this.modules = data;
          return this.$toast.saveSuccess();
        })
        .finally(() => {
          this.isLoading = false;
          reloadPage();
        });
    },
  },
};
</script>
<style lang="scss" scoped>
@import '@ohrm/oxd/styles/_mixins.scss';

.sthrm-module-field-row {
  grid-column-start: 1;
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0.75rem;
}
.sthrm-module-field-label {
  @include oxd-input-control();
  padding: 0;
  flex-basis: 75%;
}
</style>
