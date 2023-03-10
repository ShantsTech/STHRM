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
  <oxd-sheet
    v-if="!mobile"
    :gutters="false"
    type="white"
    class="sthrm-buzz-stats-modal"
  >
    <div
      v-for="user in users"
      :key="user"
      class="sthrm-buzz-stats-modal-employee"
    >
      <profile-image :employee="user.employee"></profile-image>
      <oxd-text tag="p" class="sthrm-buzz-stats-modal-employee-name">
        {{ user.fullName }}
      </oxd-text>
    </div>
    <oxd-loading-spinner v-if="isLoading" class="sthrm-buzz-loader" />
  </oxd-sheet>
  <oxd-dialog v-else class="sthrm-buzz-stats-dialog" @update:show="onClose">
    <div class="sthrm-buzz-stats-dialog-header">
      <oxd-icon
        :class="{
          'sthrm-buzz-stats-dialog-icon': true,
          '--likes': type === 'likes',
        }"
        :name="icon"
        :with-container="true"
      />
      <oxd-text v-if="type === 'shares'">
        {{ $t('buzz.n_share', {shareCount: total}) }}
      </oxd-text>
      <oxd-text v-if="type === 'likes'">
        {{ $t('buzz.n_like', {likesCount: total}) }}
      </oxd-text>
    </div>
    <oxd-divider />
    <div
      v-for="user in users"
      :key="user"
      class="sthrm-buzz-stats-dialog-employee"
    >
      <profile-image :employee="user.employee"></profile-image>
      <oxd-text tag="p" class="sthrm-buzz-stats-dialog-employee-name">
        {{ user.fullName }}
      </oxd-text>
    </div>
    <oxd-loading-spinner v-if="isLoading" class="sthrm-buzz-loader" />
  </oxd-dialog>
</template>

<script>
import {onBeforeMount, reactive, toRefs} from 'vue';
import Icon from '@ohrm/oxd/core/components/Icon/Icon';
import Sheet from '@ohrm/oxd/core/components/Sheet/Sheet';
import {APIService} from '@/core/util/services/api.service';
import Dialog from '@ohrm/oxd/core/components/Dialog/Dialog';
import Spinner from '@ohrm/oxd/core/components/Loader/Spinner';
import ProfileImage from '@/sthrmBuzzPlugin/components/ProfileImage';
import useInfiniteScroll from '@/core/util/composable/useInfiniteScroll';
import useEmployeeNameTranslate from '@/core/util/composable/useEmployeeNameTranslate';

export default {
  name: 'PostStatsModal',

  components: {
    'oxd-icon': Icon,
    'oxd-sheet': Sheet,
    'oxd-dialog': Dialog,
    'profile-image': ProfileImage,
    'oxd-loading-spinner': Spinner,
  },

  props: {
    postId: {
      type: Number,
      required: true,
    },
    type: {
      type: String,
      required: true,
    },
    icon: {
      type: String,
      required: true,
    },
    mobile: {
      type: Boolean,
      default: false,
    },
  },

  emits: ['close'],

  setup(props, context) {
    let apiPath;
    const EMPLOYEE_LIMIT = 10;
    const {$tEmpName} = useEmployeeNameTranslate();

    switch (props.type) {
      case 'likes':
        apiPath = `api/v2/buzz/shares/${props.postId}/likes`;
        break;

      case 'shares':
        apiPath = `api/v2/buzz/posts/${props.postId}/shares`;
        break;

      default:
        break;
    }

    const http = new APIService(window.appGlobal.baseUrl, apiPath);

    const state = reactive({
      total: 0,
      offset: 0,
      users: [],
      isLoading: false,
    });

    const fetchData = () => {
      state.isLoading = true;
      http
        .getAll({
          limit: EMPLOYEE_LIMIT,
          offset: state.offset,
        })
        .then(response => {
          const {data, meta} = response.data;
          state.total = meta?.total || 0;
          if (Array.isArray(data)) {
            const _data = data.map(user => {
              const {employee} = user;
              return {
                employee,
                fullName: $tEmpName(employee, {
                  includeMiddle: false,
                  excludePastEmpTag: false,
                }),
              };
            });
            state.users = [...state.users, ..._data];
          }
        })
        .finally(() => (state.isLoading = false));
    };

    useInfiniteScroll(() => {
      if (state.users.length >= state.total) return;
      state.offset += EMPLOYEE_LIMIT;
      fetchData();
    });

    onBeforeMount(() => fetchData());

    const onClose = () => {
      context.emit('close');
    };

    return {
      onClose,
      fetchData,
      ...toRefs(state),
    };
  },
};
</script>

<style lang="scss" scoped src="./post-stats-modal.scss"></style>
