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
  <!-- Mobile -->
  <template v-if="isMobile">
    <oxd-tab-container ref="swipeRef" v-model="tabSelector" :keep-alive="true">
      <oxd-tab-panel key="buzz_newsfeed" :name="$t('buzz.buzz_newsfeed')">
        <news-feed :mobile="true" :employee="employee" :sort-field="sortField">
          <post-filters
            :mobile="true"
            :filter="sortField"
            @updatePriority="onUpdatePriority"
          ></post-filters>
        </news-feed>
      </oxd-tab-panel>
      <oxd-tab-panel
        key="buzz_anniversary"
        :name="$t('buzz.upcoming_anniversaries')"
      >
        <upcoming-anniversaries></upcoming-anniversaries>
      </oxd-tab-panel>
    </oxd-tab-container>
  </template>

  <!-- Medium Res -->
  <oxd-grid v-else-if="width < 1920" :cols="2" class="sthrm-buzz-layout">
    <oxd-grid-item>
      <news-feed :employee="employee" :sort-field="sortField">
        <post-filters
          :filter="sortField"
          @updatePriority="onUpdatePriority"
        ></post-filters>
      </news-feed>
    </oxd-grid-item>
    <oxd-grid-item>
      <upcoming-anniversaries></upcoming-anniversaries>
    </oxd-grid-item>
  </oxd-grid>

  <!-- High Res -->
  <oxd-grid v-else :cols="3" class="sthrm-buzz-layout">
    <oxd-grid-item>
      <post-filters
        :filter="sortField"
        @updatePriority="onUpdatePriority"
      ></post-filters>
    </oxd-grid-item>
    <oxd-grid-item>
      <news-feed :employee="employee" :sort-field="sortField"></news-feed>
    </oxd-grid-item>
    <oxd-grid-item>
      <upcoming-anniversaries></upcoming-anniversaries>
    </oxd-grid-item>
  </oxd-grid>
</template>

<script>
import {computed, ref} from 'vue';
import useResponsive, {
  DEVICE_LG,
  DEVICE_XL,
} from '@ohrm/oxd/composables/useResponsive';
import usei18n from '@/core/util/composable/usei18n';
import useSwipe from '@/core/util/composable/useSwipe';
import TabPanel from '@ohrm/oxd/core/components/Tab/TabPanel';
import NewsFeed from '@/sthrmBuzzPlugin/components/NewsFeed.vue';
import TabContainer from '@ohrm/oxd/core/components/Tab/TabContainer';
import PostFilters from '@/sthrmBuzzPlugin/components/PostFilters.vue';
import UpcomingAnniversaries from '@/sthrmBuzzPlugin/components/UpcomingAnniversaries.vue';

export default {
  components: {
    'news-feed': NewsFeed,
    'oxd-tab-panel': TabPanel,
    'post-filters': PostFilters,
    'oxd-tab-container': TabContainer,
    'upcoming-anniversaries': UpcomingAnniversaries,
  },

  props: {
    employee: {
      type: Object,
      required: true,
    },
  },

  setup() {
    const {$t} = usei18n();
    const tabSelector = ref(null);
    const responsiveState = useResponsive();
    const sortField = ref('share.createdAtUtc');

    const isMobile = computed(() => {
      return !(
        responsiveState.screenType === DEVICE_LG ||
        responsiveState.screenType === DEVICE_XL
      );
    });

    const width = computed(() => responsiveState.windowWidth);

    const {swipeContainer} = useSwipe($event => {
      const direction = $event.offsetDirection;
      // swipe right
      if (direction === 2) {
        tabSelector.value = $t('buzz.upcoming_anniversaries');
      }

      // swipe left
      if (direction === 4) {
        tabSelector.value = $t('buzz.buzz_newsfeed');
      }
    });

    const onUpdatePriority = $event => {
      if ($event) sortField.value = $event;
    };

    return {
      width,
      isMobile,
      sortField,
      tabSelector,
      onUpdatePriority,
      swipeRef: swipeContainer,
    };
  },
};
</script>

<style src="./view-buzz.scss" lang="scss" scoped></style>
