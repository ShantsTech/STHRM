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
  <div class="sthrm-employee-tracker-log">
    <div class="sthrm-employee-tracker-log-image-section">
      <div class="sthrm-employee-tracker-log-image-wrapper">
        <img
          alt="profile picture"
          class="employee-image"
          :src="trackerLog.reviewerPictureSrc"
        />
      </div>
    </div>
    <div class="sthrm-employee-tracker-log-content-section">
      <div class="sthrm-employee-tracker-log-reviewer-name">
        <oxd-text>
          {{ trackerLog.reviewerName }}
        </oxd-text>
      </div>
      <div class="sthrm-employee-tracker-log-content-container">
        <div class="sthrm-employee-tracker-log-header">
          <div class="sthrm-employee-tracker-log-title">
            <oxd-text
              tag="h6"
              class="sthrm-employee-tracker-log-title-text"
            >
              {{ trackerLog.log }}
            </oxd-text>
            <oxd-icon
              type="svg"
              :class="{
                'sthrm-employee-tracker-log-title-icon': true,
                '--positive': trackerLog.achievement === '1',
                '--negative': trackerLog.achievement === '2',
              }"
              :name="`thumbs${trackerLog.achievement === '1' ? 'up' : 'down'}`"
            />
          </div>
          <oxd-dropdown
            v-if="trackerLog.editable"
            :options="dropdownOptions"
            @click="onTrackerDropdownAction($event, trackerLog)"
          />
        </div>
        <div class="sthrm-employee-tracker-log-body">
          <oxd-text tag="p" class="sthrm-employee-tracker-log-body-text">
            {{ trackerLog.comment }}
          </oxd-text>
        </div>
      </div>
      <div class="sthrm-employee-tracker-log-reviewer-date">
        <div class="sthrm-employee-tracker-log-reviewer-date-container">
          <oxd-icon
            class="sthrm-employee-tracker-log-reviewer-date-icon"
            name="calendar-plus"
            :title="$t('performance.added_on')"
          />
          <oxd-text>
            {{ trackerLog.addedDate }}
          </oxd-text>
        </div>
        <div
          v-if="trackerLog.modifiedDate"
          class="sthrm-employee-tracker-log-reviewer-date-container"
        >
          <oxd-icon
            class="sthrm-employee-tracker-log-reviewer-date-icon"
            name="pencil"
            :title="$t('performance.modified_on')"
          />
          <oxd-text>
            {{ trackerLog.modifiedDate }}
          </oxd-text>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Icon from '@ohrm/oxd/core/components/Icon/Icon.vue';
import Dropdown from '@ohrm/oxd/core/components/CardTable/Cell/Dropdown.vue';

export default {
  name: 'EmployeeTrackerLogCard',
  components: {
    'oxd-icon': Icon,
    'oxd-dropdown': Dropdown,
  },
  props: {
    trackerLog: {
      type: Object,
      required: true,
    },
  },
  emits: ['edit', 'delete'],
  data() {
    return {
      dropdownOptions: [
        {label: this.$t('general.edit'), context: 'edit'},
        {label: this.$t('performance.delete'), context: 'delete'},
      ],
    };
  },
  methods: {
    onTrackerDropdownAction(event, item) {
      switch (event.context) {
        case 'edit':
          this.$emit('edit', item.id);
          break;
        case 'delete':
          this.$emit('delete', item.id);
          break;
      }
    },
  },
};
</script>

<style src="./tracker-log-card.scss" lang="scss" scoped></style>
