import i18n from "../../../shared/i18n";

export default class BaseModel {
  errors = {};
  rules = {};
  attributeLabels = {};
  attributePlaceholders = {};
  attributeHints = {};

  defaultRules = {
    required: i18n.t("This field is required"),
    email: i18n.t("The email field must be a valid email"),
    regex: function (name, rule) {
      if (rule.regex.source === '^[a-zA-Z0-9]+([._@]?[a-zA-Z0-9]+)*$') {
        return i18n.t("Invalid username");
      } else if (rule.regex.source === '(?=.*[A-Z])') {
        return i18n.t("The field must contain at least one uppercase character");
      }
    },
  };

  getRules(attribute) {
    let rule = '';

    if (Array.isArray(this.rules[attribute])) {
      for (let i = 0; i < this.rules[attribute].length; i++) {
        let attr = this.rules[attribute][i];

        let isLast = !this.rules[attribute][i + 1];

        rule += this.parseRules(attr, isLast)
      }
    } else {
      let attr = this.rules[attribute]

      rule = this.parseRules(attr)
    }
    return rule;
  }

  parseRules(attr, isLast = true) {
    const required = 'required'
    const regex = 'regex'
    const email = 'email'

    let rule = '';

    if (attr.rule === required || attr.rule === email) {
      rule += attr.rule;
    }
    if (attr.rule === regex) {
      rule += attr.rule + ':' + attr.pattern;
    }

    rule += isLast ? '' : '|';

    if (attr.message) {
      this.newMessage(attr.rule, attr.message)
    }
    return rule;
  }

  newMessage(attribute, message) {
    return this.defaultRules[attribute] = i18n.t(message);
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
    return $this;
  }
}
