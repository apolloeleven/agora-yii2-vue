export default class BaseModel {
  errors = {};
  rules = {};
  attributeLabels = {};
  attributePlaceholders = {};
  attributeHints = {};

  getAttributeLabel(attribute) {
    return this.attributeLabels[attribute] || attribute;
  }

  getAttributePlaceholder(attribute) {
    return this.attributePlaceholders[attribute] || attribute;
  }

  getAttributeHint(attribute) {
    return this.attributeHints[attribute] || attribute;
  }

  hasError(attribute) {
    const errors = this.errors[attribute] || [];
    return errors.length > 0;
  }

  getFirstError(attribute) {
    if (!this.hasError(attribute)) {
      console.error(`Error does not exist for attribute "${attribute}"`);
      return '';
    }
    return this.errors[attribute];
  }

  addError(attribute, error) {
    this.errors[attribute] = this.errors[attribute] || [];
    this.errors[attribute].push(error);

    this.errors = {...this.errors};
  }

  setErrors(attribute, errors) {
    this.errors = {
      ...this.errors,
      [attribute]: errors
    };
  }

  setMultipleErrors(attributeErrorMap) {
    this.errors = {
      ...this.errors,
      ...attributeErrorMap
    };
  }

  resetErrors() {
    this.errors = {};
  }

  toJSON() {
    const $this = {...this};
    delete $this.rules;
    delete $this.errors;
    delete $this.attributeLabels;
    delete $this.attributePlaceholders;
    delete $this.attributeHints;
    return $this;
  }
}
