import i18n from "../../../shared/i18n";

export const RULE_REQUIRED = 'required'
export const RULE_REGEX = 'regex'
export const RULE_EMAIL = 'email'
export const RULE_CONFIRMED = 'confirmed'
export const RULE_MIN = 'min'

export default class BaseModel {
  errors = {};
  rules = {};
  attributeLabels = {};
  attributePlaceholders = {};
  attributeHints = {};

  defaultMessages = {
    required: i18n.t('This field is required'),
    email: i18n.t('The email field must be a valid email'),
    regex: i18n.t('Value does not match the pattern'),
    confirmed: i18n.t('Passwords do not match'),
    min: function (name, rule) {
      return i18n.t(`The field must be {val} or more`, {val: rule.length})
    }
  };

  getRules(attribute, inlineRules = null) {
    let rules = inlineRules || this.rules[attribute];
    if (!rules) {
      return {};
    }

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
    if (rule.rule === RULE_CONFIRMED) {
      return rule.rule + ':' + rule.target;
    }
    if (rule.rule === RULE_MIN) {
      return rule.rule + ':' + rule.length;
    }

    throw new Error(`Incorrect validation rule "${rule.rule}"`);
  }

  getMessages(attribute, inlineMessages = null) {
    let message = {};
    const rules = this.rules[attribute];
    if (!rules) {
      return {};
    }
    if (Array.isArray(rules)) {
      for (let rule of rules) {
        message[rule.rule] = rule.message || this.defaultMessages[rule.rule];
      }
    } else if (typeof rules === 'string') {
      message[rules] = this.defaultMessages[rules];
    } else {
      message[rules.rule] = rules.message || this.defaultMessages[rules.rule];
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
    if (!attributeErrorMap) {
      return;
    }

    if (Array.isArray(attributeErrorMap) && attributeErrorMap[0].field) {
      const errorObject = (attributeErrorMap.reduce((acc, err) => {
        acc[err.field] = err.message;
        return acc;
      }, {}));
      this.errors = {
        ...this.errors,
        ...errorObject
      };
    } else if (typeof attributeErrorMap === 'object') {
      this.errors = {
        ...this.errors,
        ...attributeErrorMap
      };
    }
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