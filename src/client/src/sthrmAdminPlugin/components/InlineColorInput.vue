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
  <oxd-input-group
    class="oxd-input-field-bottom-space"
    :message="message"
    :classes="classes"
  >
    <oxd-label :label="label" :class="classes.label" />
    <oxd-color-input
      v-bind="$attrs"
      :disabled="disabled"
      :has-error="hasError"
      :model-value="modelValue"
      dropdown-position="left"
      @update:modelValue="$emit('update:modelValue', $event)"
    />
  </oxd-input-group>
</template>

<script>
import {toRef, nextTick, computed} from 'vue';
import useField from '@ohrm/oxd/composables/useField';
import Label from '@ohrm/oxd/core/components/Label/Label';
import ColorInput from '@ohrm/oxd/core/components/Input/Color/ColorInput';

export default {
  name: 'InlineColorInput',
  components: {
    'oxd-label': Label,
    'oxd-color-input': ColorInput,
  },
  inheritAttrs: false,
  props: {
    label: {
      type: String,
      default: null,
      required: false,
    },
    rules: {
      type: Array,
      default: () => [],
      required: false,
    },
    required: {
      type: Boolean,
      default: false,
      required: false,
    },
    modelValue: {
      type: String,
      default: null,
      required: false,
    },
    disabled: {
      type: Boolean,
      default: false,
      required: false,
    },
  },
  emits: ['update:modelValue'],
  setup(props, context) {
    const disabled = toRef(props, 'disabled');
    const modelValue = toRef(props, 'modelValue');
    const initialValue = modelValue.value;

    const onReset = async () => {
      context.emit('update:modelValue', initialValue);
      await nextTick();
    };

    const {hasError, message} = useField({
      fieldLabel: props.label ?? '',
      rules: props.rules,
      modelValue,
      onReset,
      disabled,
    });

    const classes = computed(() => ({
      label: {
        'oxd-input-field-required': props.required,
      },
      message: {
        'oxd-input-field-error-message': hasError,
      },
      wrapper: {
        'sthrm-color-input-wrapper': true,
      },
    }));

    return {
      classes,
      message,
      hasError,
    };
  },
};
</script>

<style lang="scss" scoped>
::v-deep(.oxd-input-group__label-wrapper) {
  display: none;
}
::v-deep(.sthrm-color-input-wrapper) {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
::v-deep(.oxd-color-input) {
  padding: 2px;
  flex-shrink: 0;
}
.oxd-input-field-bottom-space {
  margin-bottom: 1rem;
}
</style>
