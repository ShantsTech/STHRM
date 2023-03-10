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
    <div class="sthrm-performance-review-summary">
      <oxd-text tag="h5" class="sthrm-performance-review-title">
        {{ $t('performance.review_summary') }}
      </oxd-text>
      <oxd-form :loading="loading">
        <oxd-form-row class="sthrm-performance-review-details">
          <oxd-grid :cols="3" class="sthrm-performance-review-details-grid">
            <oxd-grid-item>
              <div class="sthrm-performance-review-owner">
                <img
                  alt="profile picture"
                  class="employee-image"
                  :src="imgSrc"
                />
                <div
                  class="sthrm-performance-review-owner-employee-section"
                >
                  <div class="sthrm-performance-review-owner-employee">
                    <oxd-text
                      tag="h5"
                      class="sthrm-performance-review-owner-employee-name"
                    >
                      {{ employeeName }}
                    </oxd-text>
                    <oxd-text
                      tag="h6"
                      class="sthrm-performance-review-owner-employee-job"
                    >
                      {{ jobTitle }}
                    </oxd-text>
                  </div>
                </div>
              </div>
            </oxd-grid-item>
            <oxd-grid-item v-show="status === 4">
              <div class="sthrm-performance-review-rating">
                <oxd-text type="subtitle-2">
                  {{ $t('performance.final_rating') }}
                </oxd-text>
                <oxd-text
                  tag="h4"
                  class="sthrm-performance-review-rating-number"
                >
                  {{ finalRating }}
                </oxd-text>
              </div>
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>

        <oxd-form-row class="sthrm-performance-review-details">
          <oxd-grid :cols="3" class="sthrm-performance-review-details-grid">
            <oxd-grid-item class="sthrm-performance-review-column">
              <oxd-text type="subtitle-2">
                {{ $t('performance.review_status') }}
              </oxd-text>
              <oxd-text class="sthrm-performance-review-bold">
                {{ reviewStatus }}
              </oxd-text>
            </oxd-grid-item>
            <oxd-grid-item class="sthrm-performance-review-column">
              <oxd-text type="subtitle-2">
                {{ $t('performance.review_period') }}
              </oxd-text>
              <oxd-text class="sthrm-performance-review-bold">
                {{ reviewPeriod }}
              </oxd-text>
            </oxd-grid-item>
            <oxd-grid-item class="sthrm-performance-review-column">
              <oxd-text type="subtitle-2">
                {{ $t('performance.review_due_date') }}
              </oxd-text>
              <oxd-text class="sthrm-performance-review-bold">
                {{ reviewDueDate }}
              </oxd-text>
            </oxd-grid-item>
          </oxd-grid>
        </oxd-form-row>
      </oxd-form>
    </div>
  </div>
</template>

<script>
import {computed} from 'vue';
import {formatDate, parseDate} from '@/core/util/helper/datefns';
import useDateFormat from '@/core/util/composable/useDateFormat';
import useLocale from '@/core/util/composable/useLocale';
import usei18n from '@/core/util/composable/usei18n';
import useEmployeeNameTranslate from '@/core/util/composable/useEmployeeNameTranslate';

const defaultPic = `${window.appGlobal.baseUrl}/../dist/img/user-default-400.png`;

export default {
  name: 'ReviewSummary',

  props: {
    employee: {
      type: Object,
      required: true,
    },
    jobTitle: {
      type: String,
      required: true,
    },
    status: {
      type: Number,
      required: true,
    },
    reviewPeriodStart: {
      type: String,
      required: true,
    },
    reviewPeriodEnd: {
      type: String,
      required: true,
    },
    dueDate: {
      type: String,
      required: true,
    },
    loading: {
      type: Boolean,
      required: true,
    },
    finalRating: {
      type: Number,
      default: 0,
    },
  },
  setup(props) {
    const {$t} = usei18n();
    const {locale} = useLocale();
    const {jsDateFormat} = useDateFormat();
    const {$tEmpName} = useEmployeeNameTranslate();

    const statusOpts = [
      {id: 1, label: $t('performance.inactive')},
      {id: 2, label: $t('performance.activated')},
      {id: 3, label: $t('performance.in_progress')},
      {id: 4, label: $t('performance.completed')},
    ];

    const reviewDateFormat = date =>
      formatDate(parseDate(date), jsDateFormat, {locale});

    const imgSrc = computed(() =>
      props.employee.empNumber
        ? `${window.appGlobal.baseUrl}/pim/viewPhoto/empNumber/${props.employee.empNumber}`
        : defaultPic,
    );

    const reviewStatus = statusOpts.find(el => el.id === props.status).label;
    const reviewPeriod = `${reviewDateFormat(
      props.reviewPeriodStart,
    )} - ${reviewDateFormat(props.reviewPeriodEnd)}`;
    const reviewDueDate = reviewDateFormat(props.dueDate);

    const employeeName = computed(() => {
      return $tEmpName(props.employee, {
        includeMiddle: true,
        excludePastEmpTag: false,
      });
    });

    return {
      imgSrc,
      reviewStatus,
      reviewPeriod,
      reviewDueDate,
      employeeName,
    };
  },
};
</script>

<style src="./review-summary.scss" lang="scss" scoped></style>
