import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "@/shared/i18n";

export default class PasswordResetModel extends BaseModel {
  old_password = '';
  password = '';
  confirm_password = '';

  rules = {
    old_password: 'required',
    password: 'required',
    confirm_password: [
      'required',
      {rule: 'confirmed', target: 'password'},
    ],
  };

  attributeLabels = {
    old_password: i18n.t('Old Password'),
    password: i18n.t('Password'),
    confirm_password: i18n.t('Confirm Password'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}