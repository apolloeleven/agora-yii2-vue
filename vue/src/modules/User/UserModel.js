import BaseModel from "../../core/components/input-widget/BaseModel";
import i18n from '../../shared/i18n';

export default class LoginForm extends BaseModel {
  id = null;
  email = null;
  password = null;
  confirmPassword = null;
  firstName = null;
  lastName = null;
  phone = null;
  mobile = null;
  birthday = null;
  aboutMe = null;

  rules = {
    email: [
      {rule: 'email'},
    ],
    password: '',
    confirmPassword: '',
    firstName: '',
    lastName: '',
    phone: '',
    mobile: '',
    birthday: '',
    aboutMe: ''
  };

  attributeLabels = {
    email: i18n.t('Email'),
    password: i18n.t('Password'),
    confirmPassword: i18n.t('Confirm Password'),
    firstName: i18n.t('First Name'),
    lastName: i18n.t('Last Name'),
    birthday: i18n.t('Birthday'),
    phone: i18n.t('Phone'),
    mobile: i18n.t('Mobile'),
    aboutMe: i18n.t('About Me'),


  };

  constructor(email = '',
              password = '',
              confirmPassword = '',
              firstName = '',
              lastName = '',
              birthday = null,
              phone = '',
              mobile = '',
              aboutMe = '',
              id = '') {
    super();
    this.email = email;
    this.password = password;
    this.confirmPassword = confirmPassword;
    this.firstName = firstName;
    this.lastName = lastName;
    this.birthday = birthday;
    this.phone = phone;
    this.mobile = mobile;
    this.aboutMe = aboutMe;
    this.id = id;

  }
}