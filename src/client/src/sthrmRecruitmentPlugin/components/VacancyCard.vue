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
  <div class="sthrm-card-container">
    <div class="sthrm-vacancy-card-header">
      <oxd-text type="card-title">
        {{ vacancyTitle }}
      </oxd-text>
      <oxd-button
        :label="$t('general.apply')"
        display-type="secondary"
        class="oxd-button"
        @click="apply"
      ></oxd-button>
    </div>
    <oxd-divider v-show="vacancyDescription"></oxd-divider>
    <div :class="{'sthrm-vacancy-card-body': isViewDetails}">
      <oxd-text type="toast-message">
        <pre v-if="vacancyDescription" class="sthrm-vacancy-card-pre-tag">{{
          vacancyDescription
        }}</pre>
      </oxd-text>
    </div>
    <div
      v-if="vacancyDescription && vacancyDescription.length > descriptionLength"
      class="sthrm-vacancy-card-footer"
    >
      <a @click="viewDetails">
        <oxd-text tag="p" class="sthrm-vacancy-card-anchor-tag">
          {{
            isViewDetails ? $t('general.show_more') : $t('general.show_less')
          }}
        </oxd-text>
      </a>
    </div>
  </div>
</template>

<script>
import {toRefs} from 'vue';
import {navigate} from '@/core/util/helper/navigation';
import useResponsive from '@ohrm/oxd/composables/useResponsive';

export default {
  name: 'VacancyCard',
  props: {
    vacancyId: {
      type: Number,
      required: true,
    },
    vacancyTitle: {
      type: String,
      required: true,
    },
    vacancyDescription: {
      type: String,
      required: true,
    },
  },
  setup() {
    const responsiveState = useResponsive();
    return {
      ...toRefs(responsiveState),
    };
  },
  data() {
    return {
      viewMore: false,
    };
  },
  computed: {
    isMobile() {
      return this.windowWidth < 600;
    },
    isViewDetails() {
      return !this.viewMore;
    },
    descriptionLength() {
      if (this.isMobile) return 150;
      return this.windowWidth < 1920 ? 250 : 400;
    },
  },
  methods: {
    viewDetails() {
      this.viewMore = !this.viewMore;
    },
    apply() {
      navigate('/recruitmentApply/applyVacancy/id/{id}', {id: this.vacancyId});
    },
  },
};
</script>

<style src="../pages/public-job-vacancy.scss" lang="scss" scoped></style>
