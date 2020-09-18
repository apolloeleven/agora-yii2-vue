import BaseModel from "../../core/components/input-widget/BaseModel";
import i18n from '../../shared/i18n';

export default class RegisterForm extends BaseModel {
  firstname = '';
  lastname = '';
  password = '';
  password_repeat = '';
  email = '';
  token = '';

  rules = {
    firstname: 'required',
    lastname: 'required',
    email: 'email',
    password: [
      {rule: 'required'},
      {rule: 'min', length: 6},
    ],
    password_repeat: [
      {rule: 'required'},
      {rule: 'confirmed', target: 'password'},
    ],
  };

  attributeLabels = {
    firstname: i18n.t('First Name'),
    lastname: i18n.t('Last Name'),
    email: i18n.t('Email'),
    password: i18n.t('Password'),
    password_repeat: i18n.t('Password Repeat')
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}
