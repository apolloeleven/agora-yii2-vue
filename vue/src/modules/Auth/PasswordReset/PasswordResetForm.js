import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from '../../../shared/i18n';

export default class PasswordResetForm extends BaseModel {
  password = '';
  repeat_password = '';
  token = '';

  rules = {
    password: [
      {rule: 'required'},
    ],
    repeat_password: [
      {rule: 'required'},
      {rule: 'confirmed', target: 'password'},
    ],
  };

  attributeLabels = {
    password: i18n.t('Password'),
    repeat_password: i18n.t('Repeat password'),
  };

  constructor(password = '') {
    super();
    this.password = password;
  }
}