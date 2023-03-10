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
  <div class="sthrm-photo-input">
    <oxd-alert
      type="error"
      :show="!!validationMessage"
      :message="validationMessage"
    >
      <oxd-icon-button
        name="x"
        class="sthrm-photo-input-remove"
        @click="onClickCloseAlert"
      />
    </oxd-alert>
    <photo-upload-area v-if="showUploadArea" @update:modelValue="onFileChange">
    </photo-upload-area>
    <div class="sthrm-photo-input-field">
      <oxd-input-field
        v-if="showUploadButton"
        type="file"
        @update:modelValue="onFileChange"
      >
        <oxd-button icon-name="file-image" :label="$t('buzz.add_photos')" />
      </oxd-input-field>
    </div>

    <photo-frame :media="modelValue">
      <template #content="{index}">
        <oxd-icon-button
          name="x"
          class="sthrm-photo-input-remove --float"
          @click="onClickRemove(index)"
        />
      </template>
    </photo-frame>
  </div>
</template>

<script>
import {computed, ref} from 'vue';
import usei18n from '@/core/util/composable/usei18n';
import Alert from '@ohrm/oxd/core/components/Alert/Alert';
import PhotoFrame from '@/sthrmBuzzPlugin/components/PhotoFrame';
import {maxFileSize, validFileTypes} from '@/core/util/validation/rules';
import PhotoUploadArea from '@/sthrmBuzzPlugin/components/PhotoUploadArea';

export default {
  name: 'PhotoInput',

  components: {
    'oxd-alert': Alert,
    'photo-frame': PhotoFrame,
    'photo-upload-area': PhotoUploadArea,
  },

  props: {
    modelValue: {
      type: Array,
      required: true,
    },
  },

  emits: ['update:modelValue'],

  setup(props, context) {
    const {$t} = usei18n();
    const validationMessage = ref('');
    const fileTypeValidator = validFileTypes([
      'image/gif',
      'image/jpeg',
      'image/jpg',
      'image/pjpeg',
      'image/png',
      'image/x-png',
    ]);
    const fileSizeValidator = maxFileSize(1024 * 1024 * 2);

    const onFileChange = $file => {
      if (!$file) return;
      validationMessage.value = '';
      if (fileSizeValidator($file) !== true) {
        return (validationMessage.value = $t(
          'buzz.file_size_validation_message',
        ));
      }
      if (fileTypeValidator($file) !== true) {
        return (validationMessage.value = $t(
          'buzz.file_type_validation_message',
        ));
      }
      context.emit('update:modelValue', [...(props.modelValue || []), $file]);
    };

    const onClickRemove = index => {
      validationMessage.value = '';
      context.emit(
        'update:modelValue',
        (props.modelValue || []).filter((_, i) => index !== i),
      );
    };

    const onClickCloseAlert = () => {
      validationMessage.value = '';
    };

    const showUploadArea = computed(
      () => Array.isArray(props.modelValue) && props.modelValue.length < 1,
    );

    const showUploadButton = computed(
      () =>
        Array.isArray(props.modelValue) &&
        props.modelValue.length > 0 &&
        props.modelValue.length < 5,
    );

    return {
      onFileChange,
      onClickRemove,
      showUploadArea,
      showUploadButton,
      onClickCloseAlert,
      validationMessage,
    };
  },
};
</script>

<style src="./photo-input.scss" lang="scss" scoped></style>
