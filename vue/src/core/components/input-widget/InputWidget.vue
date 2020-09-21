<template>
  <ValidationProvider :name="`${attribute}-${uuid}`" :rules="rules || model.getRules(attribute)"
                      :customMessages="customMessages" v-slot="v" tag="div" :vid="vid">
    <b-form-group v-if="isInput() || isTextarea()">
      <label v-if="computedLabel">
        {{ computedLabel }}
        <span v-if="v.required" class="text-danger">*</span>
      </label>
      <b-input-group v-if="isInput()" :prepend="prepend" :append="append" :size="size">
        <template v-slot:append v-if="appendQuestion">
          <b-tooltip :target="`question-mark-tooltip-${attribute}-${uuid}`" :title="appendQuestion"/>
          <b-input-group-text :id="`question-mark-tooltip-${attribute}-${uuid}`" class="hover-cursor">
            <i class="fa fa-plus-question-circle "></i>
          </b-input-group-text>
        </template>
        <b-form-input v-if="type === 'number'" ref="currentInput" :size="size" :type="type" :disabled="disabled"
                      :readonly="readonly" :autofocus="autofocus" :name="`${attribute}-${uuid}`" @keyup="onKeyup"
                      :key="`${attribute}-${uuid}`" :id="inputId" v-model="model[attribute]" @change="onChange"
                      @input="onInput" @keydown="onKeydown" @blur="onBlur" :state="getState(v)"
                      :placeholder="computedPlaceholder" :min="min"/>
        <b-form-input v-else ref="currentInput" :size="size" :type="type" :disabled="disabled" :readonly="readonly"
                      :autofocus="autofocus" :name="`${attribute}-${uuid}`" :key="`${attribute}-${uuid}`"
                      :id="inputId" v-model="model[attribute]" @change="onChange" @input="onInput" @keydown="onKeydown"
                      @keyup="onKeyup" @blur="onBlur" :state="getState(v)" :placeholder="computedPlaceholder"/>
      </b-input-group>
      <b-form-textarea v-if="isTextarea()" ref="currentInput" :type="type" :name="`${attribute}-${uuid}`"
                       :key="`${attribute}-${uuid}`" v-model="model[attribute]" :state="getState(v)"
                       :placeholder="computedPlaceholder"/>
      <b-form-invalid-feedback :state="getState(v)">
        {{ getError(v.errors) }}
      </b-form-invalid-feedback>
      <b-form-text v-if="computedHint">
        {{ computedHint }}
      </b-form-text>
    </b-form-group>
  </ValidationProvider>
</template>

<script>

import BaseModel from "./BaseModel";
import {uuid} from 'vue-uuid';

export default {
  name: 'InputWidget',
  components: {},
  props: {
    model: BaseModel,
    attribute: String,
    label: {
      type: [String, Boolean],
      default: null
    },
    type: {
      type: String,
      default: 'text'
    },
    placeholder: {
      type: [String, Boolean],
      default: null
    },
    disabled: {
      type: Boolean,
      default: false
    },
    readonly: {
      type: Boolean,
      default: false
    },
    size: {
      type: String,
      default: 'md'
    },
    hint: {
      type: [Boolean, String],
      default: false
    },
    prepend: {
      type: String,
      default: ''
    },
    append: {
      type: String,
      default: ''
    },
    autofocus: {
      type: Boolean,
      default: false,
    },
    rules: {
      type: String,
      default: null,
      required: false
    },
    inputId: {
      type: String,
      default() {
        return `${this.attribute}-${uuid.v4()}`
      }
    },
    appendQuestion: {
      type: String,
      default: null
    },
    vid: {
      type: String,
      default() {
        return `vid-${this.attribute}-${uuid.v4()}`
      }
    },
    min: {
      type: [String, Number],
      default: 0
    }
  },
  data() {
    return {
      uuid: uuid.v4(),
    }
  },
  methods: {
    getError(errors) {
      if (this.model.hasError(this.attribute)) {
        return this.model.getFirstError(this.attribute);
      }
      return errors[0];
    },
    getState(validator) {
      if (this.model.hasError(this.attribute)) {
        return false;
      }

      // Validate is set when validate function of vee validate is run
      if (validator.validated === true) {
        // return validator.valid;
        return validator.valid === true ? null : false;
      }

      if (!validator.touched) {
        return null;
      }

      // return !validator.touched ? null : validator.valid
      return validator.valid === true ? null : false;
    },
    focus() {
      this.$refs.currentInput.focus();
    },
    isInput() {
      return ['text', 'number', 'date', 'password', 'email', 'search', 'url', 'tel', 'time', 'range', 'color'].includes(this.type)
    },
    isTextarea() {
      return this.type === 'textarea';
    },
    onChange(val) {
      if (this.type === 'number' && val === '') {
        this.model[this.attribute] = null;
      }
      this.$emit('change', val);
    },
    onInput($event) {
      this.$emit('input', $event);
    },
    onBlur($event) {
      if (this.type === 'number' && $event.target.value === '') {
        this.model[this.attribute] = null;
      }
      this.$emit('blur', $event);
    },
    onKeydown($event) {
      this.$emit('keydown', $event);
    },
    onKeyup($event) {
      this.$emit('keyup', $event);
    },
  },
  computed: {
    customMessages() {
      //TODO Must check this.rules and extract error messages from there
      return this.model.getMessages(this.attribute)
    },
    computedPlaceholder() {
      if (this.placeholder === false) {
        return '';
      } else if (this.placeholder === true) {
        return this.model.getAttributePlaceholder(this.attribute)
      } else {
        return this.placeholder;
      }
    },
    computedLabel() {
      if (this.label === null) {
        return this.model.getAttributeLabel(this.attribute)
      }
      return this.label;
    },
    computedHint() {
      if (!this.hint || typeof this.hint === 'string') {
        return this.hint;
      }
      return this.model.getAttributeHint(this.attribute)
    },
  }
};

</script>

<style lang="scss">

</style>
