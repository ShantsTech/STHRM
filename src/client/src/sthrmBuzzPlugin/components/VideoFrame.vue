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
  <div class="sthrm-buzz-video">
    <iframe
      :src="embedURL"
      frameborder="0"
      class="sthrm-buzz-video-frame"
      title="Shants Tech buzz video frame"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
      allowfullscreen
    ></iframe>
  </div>
</template>

<script>
export default {
  name: 'VideoFrame',
  props: {
    videoSrc: {
      type: String,
      required: true,
    },
  },
  computed: {
    embedURL() {
      return new String(this.videoSrc).trim();
    },
  },
};
</script>

<style lang="scss" scoped>
.sthrm-buzz-video {
  height: 0;
  width: 100%;
  overflow: hidden;
  position: relative;
  padding-bottom: 56.25%;
  &-frame {
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    position: absolute;
    border-radius: 0.75rem;
  }
}
</style>
