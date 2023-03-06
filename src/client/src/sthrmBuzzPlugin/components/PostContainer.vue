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
  <oxd-sheet :gutters="false" type="white" class="sthrm-buzz">
    <div class="sthrm-buzz-post">
      <div class="sthrm-buzz-post-header">
        <div class="sthrm-buzz-post-header-details">
          <profile-image :employee="post.employee"></profile-image>
          <div class="sthrm-buzz-post-header-text">
            <oxd-text tag="p" class="sthrm-buzz-post-emp-name">
              {{ employeeFullName }}
            </oxd-text>
            <oxd-text tag="p" class="sthrm-buzz-post-time">
              {{ postDateTime }}
            </oxd-text>
          </div>
        </div>
        <div
          v-if="post.permission.canUpdate || post.permission.canDelete"
          class="sthrm-buzz-post-header-config"
        >
          <oxd-dropdown>
            <oxd-icon-button name="three-dots" :with-container="true" />
            <template #content>
              <li
                v-if="post.permission.canDelete"
                class="sthrm-buzz-post-header-config-item"
                @click="$emit('delete', $event)"
              >
                <oxd-icon name="trash" />
                <oxd-text tag="p">
                  {{ $t('buzz.delete_post') }}
                </oxd-text>
              </li>
              <li
                v-if="post.permission.canUpdate"
                class="sthrm-buzz-post-header-config-item"
                @click="$emit('edit', $event)"
              >
                <oxd-icon name="pencil" />
                <oxd-text tag="p">
                  {{ $t('buzz.edit_post') }}
                </oxd-text>
              </li>
            </template>
          </oxd-dropdown>
        </div>
      </div>
      <oxd-divider />
    </div>
    <div class="sthrm-buzz-post-body">
      <slot name="content"></slot>
      <slot name="body"></slot>
    </div>
    <div class="sthrm-buzz-post-footer">
      <slot name="actionButton"></slot>
      <slot name="postStats"></slot>
    </div>
    <slot name="comments"></slot>
  </oxd-sheet>
</template>

<script>
import {computed} from 'vue';
import Icon from '@ohrm/oxd/core/components/Icon/Icon';
import useLocale from '@/core/util/composable/useLocale';
import Sheet from '@ohrm/oxd/core/components/Sheet/Sheet';
import useDateFormat from '@/core/util/composable/useDateFormat';
import {formatDate, parseDate} from '@/core/util/helper/datefns';
import ProfileImage from '@/sthrmBuzzPlugin/components/ProfileImage';
import Dropdown from '@ohrm/oxd/core/components/DropdownMenu/DropdownMenu.vue';
import useEmployeeNameTranslate from '@/core/util/composable/useEmployeeNameTranslate';

export default {
  name: 'PostContainer',
  components: {
    'oxd-icon': Icon,
    'oxd-sheet': Sheet,
    'oxd-dropdown': Dropdown,
    'profile-image': ProfileImage,
  },
  props: {
    post: {
      type: Object,
      required: true,
    },
  },

  emits: ['edit', 'delete'],

  setup(props) {
    const {locale} = useLocale();
    const {jsDateFormat} = useDateFormat();
    const {$tEmpName} = useEmployeeNameTranslate();

    const employeeFullName = computed(() => {
      return $tEmpName(props.post.employee, {
        includeMiddle: true,
        excludePastEmpTag: false,
      });
    });

    const postDateTime = computed(() => {
      const {createdDate, createdTime} = props.post;

      const utcDate = parseDate(
        `${createdDate} ${createdTime} +00:00`,
        'yyyy-MM-dd HH:mm xxx',
      );

      return formatDate(utcDate, `${jsDateFormat} HH:mm`, {
        locale,
      });
    });

    return {
      postDateTime,
      employeeFullName,
    };
  },
};
</script>

<style lang="scss" scoped src="./post-container.scss"></style>
