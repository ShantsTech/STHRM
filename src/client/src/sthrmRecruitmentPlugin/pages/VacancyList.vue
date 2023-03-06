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
  <div class="sthrm-container">
    <vacancy-card
      v-for="vacancy in vacancies?.data"
      :key="vacancy"
      :vacancy-id="vacancy.vacancyId"
      :vacancy-title="vacancy.vacancyTitle"
      :vacancy-description="vacancy.vacancyDescription"
    ></vacancy-card>
    <oxd-loading-spinner v-if="isLoading" class="sthrm-container-loader" />
    <div v-if="showPaginator" class="sthrm-bottom-container">
      <oxd-pagination v-model:current="currentPage" :length="pages" />
    </div>
  </div>
  <div class="sthrm-paper-container">
    <oxd-text tag="p" class="sthrm-vacancy-list-poweredby">
      {{ $t('recruitment.powered_by') }}
    </oxd-text>
    <img
      :src="defaultPic"
      alt="Shants Tech Picture"
      class="sthrm-container-img"
    />
    <slot name="footer"></slot>
  </div>
</template>

<script>
import VacancyCard from '@/sthrmRecruitmentPlugin/components/VacancyCard';
import {APIService} from '@/core/util/services/api.service';
import Spinner from '@ohrm/oxd/core/components/Loader/Spinner';
import usePaginate from '@/core/util/composable/usePaginate';

export default {
  name: 'VacancyList',
  components: {
    'vacancy-card': VacancyCard,
    'oxd-loading-spinner': Spinner,
  },
  setup() {
    const defaultPic = `${window.appGlobal.baseUrl}/../images/ohrm_branding.png`;
    const vacancyDataNormalizer = data => {
      return data.map(item => {
        return {
          vacancyId: item.id,
          vacancyTitle: item.name,
          vacancyDescription: item.description,
        };
      });
    };
    const http = new APIService(
      window.appGlobal.baseUrl,
      '/api/v2/recruitment/public/vacancies',
    );
    const {
      showPaginator,
      currentPage,
      total,
      pages,
      response,
      isLoading,
    } = usePaginate(http, {
      normalizer: vacancyDataNormalizer,
      pageSize: 8,
    });
    return {
      defaultPic,
      showPaginator,
      currentPage,
      isLoading,
      total,
      pages,
      vacancies: response,
    };
  },
};
</script>

<style src="./public-job-vacancy.scss" lang="scss" scoped></style>
