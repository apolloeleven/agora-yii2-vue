import BaseModel from "../../core/components/input-widget/BaseModel";
import i18n from '../../shared/i18n';

export default class UserModel extends BaseModel {
  email = '';
  password = '';
  confirmPassword = '';
  firstName = '';
  lastName = '';
  phone = '';
  mobile = '';
  birthday = '';
  aboutMe = '';
  hobbies = '';

  rules = {
    email: [
      {rule: 'email'},
      {rule: 'required'},
    ],
    password: '',
    confirmPassword: [
      {rule: 'confirmed', target: 'password'},
    ],
    firstName: '',
    lastName: '',
    phone: '',
    mobile: '',
    birthday: '',
    aboutMe: '',
    hobbies: ''
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
    hobbies: i18n.t('Hobbies'),
  };

  constructor(email = '',
              password = '',
              firstName = '',
              lastName = '',
              birthday = null,
              phone = '',
              mobile = '',
              aboutMe = '',
              hobbies = '') {
    super();
    this.email = email;
    this.password = password;
    this.firstName = firstName;
    this.lastName = lastName;
    this.birthday = birthday;
    this.phone = phone;
    this.mobile = mobile;
    this.aboutMe = aboutMe;
    this.hobbies = hobbies;
  }
}