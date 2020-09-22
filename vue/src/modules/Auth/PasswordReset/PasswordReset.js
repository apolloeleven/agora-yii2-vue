import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from '../../../shared/i18n';

export default class PasswordReset extends BaseModel {
  email = null;

  rules = {
    email: [
      {rule: 'required'},
      {rule: 'email'},
    ]
  };

  attributeLabels = {
    email: i18n.t('Email'),
  };

  constructor(email = '') {
    super();
    this.email = email;
  }
}