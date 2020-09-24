import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class EmployeeListFormModel extends BaseModel {
  firstName = null;
  lastName = null;
  email = null;

  rules = {
    firstName: [
      {rule: 'required'},
    ],
    lastName: [
      {rule: 'required'}
    ],
    email: [
      {rule: 'required'},
      {rule: 'email'},
    ],
  }

  attributeLabels = {
    email: i18n.t('Email'),
    firstName: i18n.t('First Name'),
    lastName: i18n.t('Last Name'),
  };

  constructor(email = '', firstName = '', lastName = '') {
    super();
    this.email = email;
    this.firstName = firstName;
    this.lastName = lastName;
  }
}