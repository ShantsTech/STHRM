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
      Database Configuration
    </oxd-text>
    <br />
    <oxd-text tag="p" class="shantshrm-installer-page-content">
      Please enter your database configuration information below. If you are
      unsure of what to fill in, we suggest that you use the default values.
    </oxd-text>
    <br />
    <oxd-text tag="p" class="shantshrm-installer-page-content">
      Select Database to Use
    </oxd-text>
    <oxd-form-row class="shantshrm-database-info-row">
      <oxd-radio-input
        v-model="database.dbType"
        value="new"
        option-label="New Database"
      />
      <oxd-radio-input
        v-model="database.dbType"
        value="existing"
        option-label="Existing Empty Database"
      />
    </oxd-form-row>
    <br />

    <oxd-grid :cols="4" class="shantshrm-full-width-grid">
      <oxd-grid-item>
        <oxd-input-field
          v-model="database.dbHost"
          label="Database Host Name"
          :rules="rules.dbHost"
          required
        />
      </oxd-grid-item>
      <oxd-grid-item class="shantshrm-database-info-port">
        <oxd-input-field
          v-model="database.dbPort"
          label="Database Host Port"
          :rules="rules.dbPort"
          required
        />
      </oxd-grid-item>
      <oxd-grid-item class="--offset-row-2">
        <oxd-input-field
          v-model="database.dbName"
          label="Database Name"
          :rules="rules.dbName"
          required
        />
      </oxd-grid-item>
      <oxd-grid-item
        v-if="isNewDB"
        class="--offset-row-2 shantshrm-database-info-check"
      >
        <oxd-input-field
          v-model="database.useSameDbUserForShantsHRM"
          label="&nbsp;"
          type="checkbox"
          option-label="Use the same Database User for ShantsHRM"
        />
      </oxd-grid-item>
    </oxd-grid>
    <oxd-grid
      :cols="4"
      class="shantshrm-full-width-grid shantshrm-database-info-user"
    >
      <template v-if="isNewDB">
        <oxd-grid-item>
          <oxd-input-field
            v-model="database.dbUser"
            v-tooltip="
              'Privileged Database User should have the rights to create databases, create tables, insert data into table, alter table structure and to create database users.'
            "
            label="Privileged Database Username"
            :rules="rules.dbUser"
            required
          />
        </oxd-grid-item>
        <oxd-grid-item>
          <oxd-input-field
            v-model="database.dbPassword"
            label="Privileged Database User Password"
            type="password"
          />
        </oxd-grid-item>
      </template>
      <oxd-grid-item>
        <oxd-input-field
          :key="disableOHRMDBfield"
          v-model="database.ohrmDbUser"
          v-tooltip="
            'ShantsHRM database user should have the rights to insert data into table, update data in a table, delete data in a table.'
          "
          label="ShantsHRM Database Username"
          :rules="rules.ohrmDbUser"
          :disabled="disableOHRMDBfield"
          :required="!disableOHRMDBfield"
        />
      </oxd-grid-item>
      <oxd-grid-item>
        <oxd-input-field
          v-model="database.ohrmDbPassword"
          :disabled="disableOHRMDBfield"
          label="ShantsHRM Database User Password"
          type="password"
        />
      </oxd-grid-item>
    </oxd-grid>
    <oxd-grid :cols="4" class="shantshrm-full-width-grid">
      <oxd-grid-item>
        <oxd-input-field
          v-model="database.enableDataEncryption"
          label="&nbsp;"
          type="checkbox"
          option-label="Enable Data Encryption"
        />
      </oxd-grid-item>
    </oxd-grid>
    <oxd-text class="shantshrm-installer-page-content">
      Click <b>Next</b> to continue
    </oxd-text>
    <br />
    <oxd-text
      v-show="errorMessage"
      class="shantshrm-installer-page-content shantshrm-database-info-error"
    >
      <b>{{ errorMessage }}</b>
    </oxd-text>

    <oxd-form-actions class="shantshrm-installer-page-action">
      <required-text />
      <oxd-button
        display-type="ghost"
        label="Back"
        type="button"
        @click="navigateUrl"
      />
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
import {
  required,
  validRange,
  shouldNotExceedCharLength,
  shouldNotContainSpecialChars,
} from '@/core/util/validation/rules';
import {APIService} from '@/core/util/services/api.service';
import {navigate} from '@/core/util/helper/navigation.ts';
import RadioInput from '@ohrm/oxd/core/components/Input/RadioInput';
import tooltipDirective from '@/core/util/directives/tooltip';

export default {
  name: 'DatabaseConfigScreen',
  components: {
    'oxd-radio-input': RadioInput,
  },
  directives: {
    tooltip: tooltipDirective,
  },
  setup() {
    const http = new APIService(
      window.appGlobal.baseUrl,
      '/installer/api/database-config',
    );
    return {
      http,
    };
  },
  data() {
    return {
      rules: {
        dbHost: [required],
        dbPort: [required, validRange(5, 0, 65535)],
        dbName: [
          required,
          shouldNotExceedCharLength(64),
          shouldNotContainSpecialChars(
            'Database name should not contain special characters',
          ),
        ],
        dbUser: [required],
        ohrmDbUser: [(value) => this.disableOHRMDBfield || required(value)],
      },
      isLoading: false,
      database: {
        dbType: null,
        dbHost: null,
        dbPort: null,
        dbUser: null,
        dbName: null,
        dbPassword: null,
        ohrmDbUser: null,
        ohrmDbPassword: null,
        useSameDbUserForShantsHRM: true,
        enableDataEncryption: false,
      },
      errorMessage: '',
    };
  },
  computed: {
    isNewDB() {
      return this.database.dbType === 'new';
    },
    disableOHRMDBfield() {
      if (!this.isNewDB) return false;
      return this.database.useSameDbUserForShantsHRM;
    },
  },
  beforeMount() {
    this.isLoading = true;
    this.http.getAll().then((response) => {
      const {data} = response.data;
      this.database = {...data, dbPassword: null, ohrmDbPassword: null};
      if (!this.database.dbType) {
        this.database.dbType = 'new';
      }
      if (!this.database.dbPort) {
        this.database.dbPort = 3306;
      }
      this.isLoading = false;
    });
  },
  methods: {
    onSubmit() {
      this.isLoading = true;
      this.errorMessage = '';
      const payload = {...this.database};
      this.http
        .create({
          ...payload,
          ...(payload.dbType === 'existing' && {
            dbUser: payload.ohrmDbUser,
            dbPassword: payload.ohrmDbPassword,
            ohrmDbUser: undefined,
            ohrmDbPassword: undefined,
            useSameDbUserForShantsHRM: undefined,
          }),
          ...(payload.useSameDbUserForShantsHRM && {
            ohrmDbUser: undefined,
            ohrmDbPassword: undefined,
          }),
        })
        .then(() => {
          navigate('/installer/system-check');
        })
        .catch(({response}) => {
          const {error} = response.data;
          this.errorMessage = error?.message ?? error;
          this.isLoading = false;
        });
    },
    navigateUrl() {
      navigate('/installer/licence-acceptance');
    },
  },
};
</script>
<style src="./installer-page.scss" lang="scss" scoped></style>
<style lang="scss" scoped>
@import '@ohrm/oxd/styles/_mixins.scss';

::v-deep(.oxd-checkbox-wrapper span) {
  flex-shrink: 0;
}
::v-deep(.oxd-radio-wrapper label) {
  margin-left: -0.5rem;
  margin-right: 1rem;
}
.shantshrm-database-info-row {
  margin-top: 5px;
  flex-direction: row;
}
.shantshrm-database-info-check {
  display: flex;
  align-items: center;
  @include oxd-respond-to('lg') {
    grid-column: 3 / full;
  }
}
.shantshrm-database-info-port {
  width: 50%;
  white-space: nowrap;
}
.shantshrm-database-info-error {
  color: $oxd-feedback-danger-color;
}
::v-deep(.shantshrm-database-info-user .oxd-label) {
  @include oxd-respond-to('lg') {
    width: 70%;
  }
}
</style>
