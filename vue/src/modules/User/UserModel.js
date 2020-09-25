import BaseModel from "../../core/components/input-widget/BaseModel";
import i18n from '../../shared/i18n';

export default class UserModel extends BaseModel {
  email = '';
  password = '';
  confirm_password = '';
  first_Name = '';
  last_name = '';
  phone = '';
  mobile = '';
  birthday = '';
  about_me = '';
  image = null;
  hobbies = [];

  rules = {
    email: [
      {rule: 'email'},
      {rule: 'required'},
    ],
    password: '',
    confirm_password: [
      {rule: 'confirmed', target: 'password'},
    ],
    first_name: '',
    last_name: '',
    phone: '',
    mobile: '',
    birthday: '',
    about_me: '',
    hobbies: '',
    image: ''
  };

  attributeLabels = {
    email: i18n.t('Email'),
    password: i18n.t('Password'),
    confirm_password: i18n.t('Confirm Password'),
    first_name: i18n.t('First Name'),
    last_name: i18n.t('Last Name'),
    birthday: i18n.t('Birthday'),
    phone: i18n.t('Phone'),
    mobile: i18n.t('Mobile'),
    about_me: i18n.t('About Me'),
    hobbies: i18n.t('Hobbies'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}