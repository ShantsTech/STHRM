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
  <oxd-button
    display-type="text"
    :label="label"
    :class="buttonClasses"
    @click="$emit('click')"
  >
    <template #icon>
      <oxd-icon type="svg" :name="iconName" :class="iconClasses" />
    </template>
  </oxd-button>
</template>

<script>
import Icon from '@ohrm/oxd/core/components/Icon/Icon.vue';

export default {
  name: 'TrackerLogRatingButton',
  components: {
    'oxd-icon': Icon,
  },
  props: {
    label: {
      type: String,
      required: true,
    },
    selected: {
      type: Boolean,
      required: true,
    },
    type: {
      type: String,
      required: true,
      validator: function(value) {
        return ['positive', 'negative'].indexOf(value) !== -1;
      },
    },
  },
  emits: ['click'],
  computed: {
    iconName() {
      return `thumbs${this.type === 'positive' ? 'up' : 'down'}`;
    },
    buttonClasses() {
      return {
        'sthrm-tracker-rating-button': true,
        '--deselected': !this.selected,
      };
    },
    iconClasses() {
      return {
        'sthrm-tracker-rating-icon': true,
        [`--${this.type}`]: true,
      };
    },
  },
};
</script>

<style lang="scss" scoped>
.sthrm-tracker-rating {
  &-button {
    margin-right: 0.6rem;
    padding-right: 0.6rem;
    padding-left: 0.6rem;
  }

  &-icon {
    vertical-align: bottom;
  }
}

.--positive {
  color: $oxd-feedback-success-color;
}

.--negative {
  color: $oxd-feedback-danger-color;
}

.--deselected {
  background-color: $oxd-white-color;
}
</style>
