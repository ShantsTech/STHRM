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
  <oxd-text
    :class="{
      'sthrm-card-note': true,
      'sthrm-card-note--background': hasNote,
      'sthrm-card-note--icon': hasNoteIcon,
    }"
  >
    <oxd-text v-if="hasNote" class="sthrm-sub-title">
      {{ $t('general.note') }}:
    </oxd-text>
    <oxd-text v-if="hasNoteIcon" tag="span" class="sthrm-card-note-icon">
      <oxd-icon name="exclamation-triangle" class="sthrm-card-icon" />
    </oxd-text>
    <oxd-text :class="{'sthrm-card-note-text': hasNoteIcon}">
      {{ noteText }}
    </oxd-text>
  </oxd-text>
</template>

<script>
import Icon from '@ohrm/oxd/core/components/Icon/Icon.vue';

export default {
  name: 'CardNote',
  components: {
    'oxd-icon': Icon,
  },
  props: {
    noteText: {
      type: String,
      required: true,
    },
    hasNote: {
      type: Boolean,
      default: false,
    },
    hasNoteIcon: {
      type: Boolean,
      required: false,
    },
  },
};
</script>

<style scoped lang="scss">
@import '@ohrm/oxd/core/components/Alert/_variables.scss';

.sthrm-card {
  &-icon {
    font-size: 1.5rem;
    color: $oxd-feedback-danger-color;
    padding: 1rem;
  }

  &-note {
    font-size: $oxd-input-control-font-size;

    &-text {
      color: $oxd-feedback-danger-color;
    }
    &-icon {
      flex-shrink: 0;
    }
  }

  &-note--background {
    background-color: $oxd-interface-gray-lighten-2-color;
    border-radius: $oxd-border-radius;
    padding: 1.2rem;
  }

  &-note--icon {
    display: flex;
    align-items: center;
    background-color: $oxd-alert-bg-color-error;
    border-radius: $oxd-border-radius;
    padding: 1rem;
  }
}
</style>
