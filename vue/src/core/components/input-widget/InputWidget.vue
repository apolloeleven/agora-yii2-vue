<template>
  <ValidationProvider :name="`${attribute}-${uuid}`" :rules="model.getRules(attribute, rules || null)"
                      :customMessages="model.getMessages(attribute, rules || null)" v-slot="v"
                      tag="div" :vid="vid || attribute">
    <b-form-group v-if="isInput() || isTextarea()">
      <label v-if="computedLabel">
        {{ computedLabel }}
        <span v-if="v.required" class="text-danger">*</span>
      </label>
      <b-input-group v-if="isInput()" :prepend="prepend" :append="append" :size="size">
        <template v-slot:append v-if="appendQuestion">
          <b-tooltip :target="`question-mark-tooltip-${attribute}-${uuid}`" :title="appendQuestion"/>
          <b-input-group-text :id="`question-mark-tooltip-${attribute}-${uuid}`" class="hover-cursor">
            <i class="fas fa-question-circle"></i>
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
    <b-form-group v-if="isSelect() || isDate() || isMultiselect() || isRichtext()">
      <label v-if="computedLabel">
        {{ computedLabel }}
        <span v-if="v.required" class="text-danger">*</span>
      </label>
      <b-input-group :prepend="prepend" :append="append" :size="size">
        <template v-slot:append v-if="appendQuestion">
          <b-tooltip :target="`question-mark-tooltip-${attribute}-${uuid}`" :title="appendQuestion"/>
          <b-input-group-text :id="`question-mark-tooltip-${attribute}-${uuid}`" class="hover-cursor">
            <i class="fas fa-question-circle"></i>
          </b-input-group-text>
        </template>

        <b-form-select v-if="isSelect()" ref="currentInput" :size="size" :disabled="disabled" :options="selectOptions"
                       :readonly="readonly" :autofocus="autofocus" :name="`${attribute}-${uuid}`" @keyup="onKeyup"
                       :key="`${attribute}-${uuid}`" :id="inputId" v-model="model[attribute]" @change="onChange"
                       @input="onInput" @keydown="onKeydown" @blur="onBlur" :state="getState(v)" :value-field="valueField"
                       :text-field="textField"
        />

        <datePicker v-if="isDate()" ref="currentInput" :size="size" :disabled="disabled" :config="datePickerOptions"
                    :readonly="readonly" :autofocus="autofocus" :name="`${attribute}-${uuid}`" @keyup="onKeyup"
                    :key="`${attribute}-${uuid}`" :id="inputId" v-model="model[attribute]" @change="onChange"
                    @input="onInput" @keydown="onKeydown" @blur="onBlur" :state="getState(v)"
        />

        <Multiselect v-if="isMultiselect()" ref="currentInput" :size="size" :disabled="disabled"
                     :readonly="readonly" :autofocus="autofocus" :name="`${attribute}-${uuid}`"
                     :key="`${attribute}-${uuid}`" :id="inputId" v-model="model[attribute]" :state="getState(v)"
                     :tag-placeholder="$t(multiselectPlaceholder)"
                     :placeholder="computedPlaceholder"
                     :options="multiselectOptions"
                     :multiple="true"
                     :taggable="true"
                     :selectLabel="$t('Press enter to select')"
                     :deselectLabel="$t('Press enter to remove')"
                     :selectedLabel="$t('Selected')"
                     track-by="value"
                     label="text"
                     @tag="addMultiselect">
          <span slot="noOptions">{{ $t('List is empty.') }}</span>
          <template slot="tag" slot-scope="{ option, remove }">
                            <span class="multiselect__tag">
                              <span>{{ $t(option.value) }}</span>
                              <span class="multiselect__tag-icon" @click="remove(option)"></span>
                            </span>
          </template>
        </Multiselect>

        <ckeditor v-if="isRichtext()" :editor="editor" :config="editorConfig" ref="currentInput" :size="size"
                  :disabled="disabled" :readonly="readonly" :autofocus="autofocus" :name="`${attribute}-${uuid}`"
                  :key="`${attribute}-${uuid}`" :id="inputId" v-model="model[attribute]"
        />

        <b-form-invalid-feedback :state="getState(v)">
          {{ getError(v.errors) }}
        </b-form-invalid-feedback>
      </b-input-group>
    </b-form-group>
    <b-form-group v-if="isTagsInput()">
      <label v-if="computedLabel">
        {{ computedLabel }}
        <span v-if="v.required" class="text-danger">*</span>
      </label>
      <b-input-group :prepend="prepend" :append="append" :size="size">
        <template v-slot:append v-if="appendQuestion">
          <b-tooltip :target="`question-mark-tooltip-${attribute}-${uuid}`" :title="appendQuestion"/>
          <b-input-group-text :id="`question-mark-tooltip-${attribute}-${uuid}`" class="hover-cursor">
            <i class="fas fa-question-circle"></i>
          </b-input-group-text>
        </template>
        <b-form-tags ref="currentInput" :size="size" :disabled="disabled"
                     :readonly="readonly" :autofocus="autofocus" :name="`${attribute}-${uuid}`" @keyup="onKeyup"
                     :key="`${attribute}-${uuid}`" :id="inputId" v-model="model[attribute]" @change="onChange"
                     @input="onInput" @keydown="onKeydown" @blur="onBlur" :state="getState(v)"
                     :placeholder="computedPlaceholder" :min="min"/>
      </b-input-group>
    </b-form-group>
    <b-form-group v-if="isCheckbox()">
      <b-form-checkbox ref="currentInput" :disabled="disabled" :name="`${attribute}-${uuid}`" @change="onChange"
                       :key="`${attribute}-${uuid}`" v-model="model[attribute]" :switch="isSwitch" :size="size"
                       :state="getState(v)">
        <span v-if="computedLabel">
          {{ computedLabel }}
          <span v-if="v.required" class="text-danger">*</span>
        </span>
      </b-form-checkbox>
      <b-form-invalid-feedback :state="getState(v)">{{ getError(v.errors) }}
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
import datePicker from 'vue-bootstrap-datetimepicker';
import 'pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.css';
import Multiselect from 'vue-multiselect'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
  name: 'InputWidget',
  components: {datePicker, Multiselect},
  props: {
    model: BaseModel,
    attribute: String,
    multiselectOptions: {
      type: Array,
      default: Array
    },
    multiselectPlaceholder: {
      type: String,
      default: '',
      required: false
    },
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
      type: [String, Object, Array],
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
      default: ''
    },
    min: {
      type: [String, Number],
      default: 0
    },
    selectOptions: {
      type: Array,
      default: () => []
    },
    isSwitch: {
      type: Boolean,
      default: false
    },
    valueField: {
      type: String,
      default: 'value'
    },
    textField: {
      type: String,
      default: 'text'
    }
  },
  data() {
    return {
      uuid: uuid.v4(),
      datePickerOptions: {
        format: 'DD-MM-YYYY'
      },
      editor: ClassicEditor,
      editorConfig: {},
    }
  },
  methods: {
    addMultiselect(newMultiselect) {
      const tag = {
        value: newMultiselect,
        text: newMultiselect
      };
      this.model[this.attribute].push(tag);
    },
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
      return ['text', 'number', 'password', 'email', 'search', 'url', 'tel', 'time', 'range', 'color'].includes(this.type)
    },
    isRichtext() {
      return this.type === 'richtext';
    },
    isTagsInput() {
      return this.type === 'tags';
    },
    isMultiselect() {
      return this.type === 'multiselect';
    },
    isDate() {
      return this.type === 'date';
    },
    isTextarea() {
      return this.type === 'textarea';
    },
    isSelect() {
      return this.type === 'select';
    },
    isCheckbox() {
      return this.type === 'checkbox';
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
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>

<style lang="scss">
.ck.ck-reset.ck-editor.ck-rounded-corners {
  width: 100%;
}
</style>
