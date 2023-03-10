<template>
  <oxd-dialog
    :style="{width: '90%', maxWidth: '450px'}"
    @update:show="onCancel"
  >
    <div class="sthrm-modal-header">
      <oxd-text type="card-title">
        {{ $t('time.add_customer') }}
      </oxd-text>
    </div>
    <oxd-divider />
    <oxd-form :loading="isLoading" @submitValid="onSave">
      <oxd-form-row>
        <oxd-input-field
          v-model="customer.name"
          :label="$t('general.name')"
          :rules="rules.name"
          required
        />
      </oxd-form-row>
      <oxd-form-row>
        <oxd-input-field
          v-model="customer.description"
          type="textarea"
          :label="$t('general.description')"
          placeholder="Type description here"
          :rules="rules.description"
        />
      </oxd-form-row>
      <oxd-divider />
      <oxd-form-actions class="sthrm-form-action">
        <required-text />
        <oxd-button
          display-type="ghost"
          :label="$t('general.cancel')"
          @click="onCancel"
        />
        <oxd-button
          display-type="secondary"
          :label="$t('general.save')"
          type="submit"
        />
      </oxd-form-actions>
    </oxd-form>
  </oxd-dialog>
</template>

<script>
import {APIService} from '@ohrm/core/util/services/api.service';
import {
  required,
  shouldNotExceedCharLength,
} from '@ohrm/core/util/validation/rules';
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';
import promiseDebounce from '@ohrm/oxd/utils/promiseDebounce';

const customerModel = {
  id: '',
  name: '',
  description: '',
};

export default {
  name: 'AddCustomerModal',
  components: {
    'oxd-dialog': Dialog,
  },
  emits: ['close'],
  setup() {
    const http = new APIService(
      window.appGlobal.baseUrl,
      '/api/v2/time/customers',
    );
    http.setIgnorePath('api/v2/time/validation/customer-name');
    return {
      http,
    };
  },
  data() {
    return {
      isLoading: false,
      customer: {...customerModel},
      rules: {
        name: [
          required,
          shouldNotExceedCharLength(50),
          promiseDebounce(this.validateCustomerName, 500),
        ],
        description: [shouldNotExceedCharLength(255)],
      },
    };
  },
  methods: {
    onSave() {
      this.isLoading = true;
      this.http
        .create({
          name: this.customer.name,
          description: this.customer.description,
        })
        .then(response => {
          const {data} = response.data;
          this.$toast.saveSuccess();
          this.$emit('close', data);
        });
    },
    onCancel() {
      this.$emit('close');
    },
    validateCustomerName(customer) {
      return new Promise(resolve => {
        if (customer) {
          this.http
            .request({
              method: 'GET',
              url: `api/v2/time/validation/customer-name`,
              params: {
                customerName: this.customer.name.trim(),
              },
            })
            .then(response => {
              const {data} = response.data;
              return data.valid === true
                ? resolve(true)
                : resolve(this.$t('general.already_exists'));
            });
        } else {
          resolve(true);
        }
      });
    },
  },
};
</script>
