import i18n from "../../../shared/i18n";

export const RULE_REQUIRED = 'required'
export const RULE_REGEX = 'regex'
export const RULE_EMAIL = 'email'

export default class BaseModel {
  errors = {};
  rules = {};
  attributeLabels = {};
  attributePlaceholders = {};
  attributeHints = {};

  defaultMessages = {
    required: i18n.t("This field is required"),
    email: i18n.t("The email field must be a valid email"),
    regex: i18n.t('Value does not match the pattern')
  };

  getRules(attribute) {
    let rules = this.rules[attribute]

    if (Array.isArray(rules)) {
      return rules.map(rule => this.parseRules(rule)).join('|');
    } else {
      return this.parseRules(rules)
    }
  }

  parseRules(rule) {

    if (typeof rule === 'string') {
      return rule;
    }

    if ([RULE_REQUIRED, RULE_EMAIL].includes(rule.rule)) {
      return rule.rule;
    }
    if (rule.rule === RULE_REGEX) {
      return rule.rule + ':' + rule.pattern;
    }

    throw new Error(`Incorrect validation rule "${rule.rule}"`);
  }

  getMessages(attribute) {
    let message = {};
    let ruleAttributes = this.rules[attribute]

    if (Array.isArray(ruleAttributes)) {
      for (let i = 0; i < ruleAttributes.length; i++) {
        message[ruleAttributes[i].rule] = ruleAttributes[i].message || this.defaultMessages[ruleAttributes[i].rule];
      }
    } else {
      message[ruleAttributes.rule] = ruleAttributes.message;
    }
    return message;
  }

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
    delete $this.defaultMessages;
    return $this;
  }
}