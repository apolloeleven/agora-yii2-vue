import BaseModel from "../../core/components/input-widget/BaseModel";
import i18n from '../../shared/i18n';

export default class LoginModel extends BaseModel {
  email = null;
  password = null;

  rules = {
    email: [
      {rule: 'required'},
      {rule: 'email',  message: i18n.t('This must be valid email')},
    ],
    password: 'required',
  };

  attributeLabels = {
    email: i18n.t('Email'),
    password: i18n.t('Password')
  };

  constructor(email = '', password = '') {
    super();
    this.email = email;
    this.password = password;
  }
}
