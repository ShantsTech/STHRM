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
  <div :class="layoutClasses">
    <photo-viewer
      v-if="showPhoto"
      :post="post"
      :photo-index="photoIndex"
      @close="$emit('close', false)"
    >
      <post-actions-pill
        v-if="mobile"
        :post="post"
        @like="$emit('like')"
        @comment="onClickComment"
      ></post-actions-pill>
    </photo-viewer>
    <post-details
      v-if="showDetails"
      :post="post"
      :mobile="mobile"
      @like="$emit('like')"
      @close="$emit('close', false)"
      @create-comment="$emit('createComment', $event)"
      @delete-comment="$emit('deleteComment', $event)"
    >
    </post-details>
  </div>
</template>

<script>
import {computed, reactive, toRefs} from 'vue';
import PhotoViewer from '@/sthrmBuzzPlugin/components/PhotoViewer';
import PostDetails from '@/sthrmBuzzPlugin/components/PostDetails';
import PostActionsPill from '@/sthrmBuzzPlugin/components/PostActionsPill';

export default {
  name: 'PhotoCarousel',

  components: {
    'photo-viewer': PhotoViewer,
    'post-details': PostDetails,
    'post-actions-pill': PostActionsPill,
  },

  props: {
    post: {
      type: Object,
      required: true,
    },
    mobile: {
      type: Boolean,
      default: false,
    },
    photoIndex: {
      type: Number,
      required: true,
    },
  },

  emits: ['like', 'close', 'createComment', 'deleteComment'],

  setup(props) {
    const state = reactive({
      view: 'photo',
      index: props.photoIndex,
    });

    const onClickNextPhoto = () => state.index++;

    const onClickPreviousPhoto = () => state.index--;

    const onClickComment = () => (state.view = 'details');

    const selectedPhoto = computed(() => props.post.photoIds[state.index]);

    const layoutClasses = computed(() => ({
      'sthrm-photo-carousel': true,
      '--web': props.mobile === false,
    }));

    const showPhoto = computed(
      () => props.mobile === false || state.view === 'photo',
    );

    const showDetails = computed(
      () => props.mobile === false || state.view === 'details',
    );

    return {
      showPhoto,
      showDetails,
      layoutClasses,
      selectedPhoto,
      onClickComment,
      onClickNextPhoto,
      onClickPreviousPhoto,
      ...toRefs(state),
    };
  },
};
</script>

<style src="./photo-carousel.scss" lang="scss" scoped></style>
