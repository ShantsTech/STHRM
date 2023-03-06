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
  <oxd-input-group>
    <template #label>
      <div class="sthrm-leave-balance">
        <oxd-label :label="$t('leave.leave_balance')" />
        <oxd-icon-button
          v-if="leaveData.type"
          class="--help"
          name="question-circle"
          :with-container="false"
          @click="onModalOpen"
        />
      </div>
    </template>
    <oxd-text v-if="balance >= 0" class="sthrm-leave-balance-text" tag="p">
      {{ leaveBalance }}
    </oxd-text>
    <oxd-text v-else class="sthrm-leave-balance-text --error" tag="p">
      {{ $t('leave.balance_not_sufficient') }}
    </oxd-text>
  </oxd-input-group>
  <component
    :is="leaveBalanceModal"
    v-if="showModal"
    :data="data"
    :meta="meta"
    @close="onModalClose"
  ></component>
</template>

<script>
import {toRefs, reactive, computed, watchPostEffect} from 'vue';
import {APIService} from '@ohrm/core/util/services/api.service';
import Label from '@ohrm/oxd/core/components/Label/Label';
import LeaveBalanceModal from '@/sthrmLeavePlugin/components/LeaveBalanceModal';
import LeaveBalanceInsufficientModal from '@/sthrmLeavePlugin/components/LeaveBalanceInsufficientModal';
import useLeaveValidators from '@/sthrmLeavePlugin/util/composable/useLeaveValidators';

export default {
  name: 'LeaveBalance',
  components: {
    'oxd-label': Label,
    'leave-balance-modal': LeaveBalanceModal,
    'leave-balance-insufficient-modal': LeaveBalanceInsufficientModal,
  },
  inheritAttrs: false,
  props: {
    leaveData: {
      type: Object,
      default: () => ({}),
    },
  },
  setup(props) {
    const state = reactive({
      data: null,
      meta: null,
      balance: 0,
      showModal: false,
    });
    const http = new APIService(
      window.appGlobal.baseUrl,
      'api/v2/leave/leave-balance/leave-type',
    );
    http.setIgnorePath('api/v2/leave/leave-balance/leave-type');
    const {validateLeaveBalance} = useLeaveValidators(http);

    const leaveBalance = computed(() => {
      return props.leaveData.type?.id
        ? `${state.balance.toFixed(2)} Day(s)`
        : '0.00 Day(s)';
    });

    const onModalOpen = () => {
      state.showModal = true;
    };

    const onModalClose = () => {
      state.showModal = false;
    };

    const leaveBalanceModal = computed(() => {
      return Array.isArray(state.data)
        ? 'leave-balance-insufficient-modal'
        : 'leave-balance-modal';
    });

    watchPostEffect(async () => {
      if (props.leaveData.type?.id) {
        validateLeaveBalance(props.leaveData)
          .then(({balance, breakdown, metaData}) => {
            state.balance = balance;
            if (breakdown) state.data = breakdown;
            if (metaData) state.meta = metaData;
          })
          .catch(() => {
            state.data = null;
            state.meta = null;
            state.balance = 0;
          });
      }
    });

    return {
      ...toRefs(state),
      leaveBalance,
      onModalOpen,
      onModalClose,
      leaveBalanceModal,
    };
  },
};
</script>

<style lang="scss" scoped>
.sthrm-leave-balance {
  display: flex;
  align-items: center;
  & .--help {
    margin-left: 5px;
  }
}
.sthrm-leave-balance-text {
  padding: $oxd-input-control-vertical-padding 0rem;
  &.--error {
    color: $oxd-feedback-danger-color;
  }
}
</style>
