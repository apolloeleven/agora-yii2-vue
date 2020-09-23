import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from '../../../shared/i18n';

export default class UserFormModel extends BaseModel {
  id = null;
  email = null;
  password = '';
  status = false;

  rules = {
    email: 'email',
    password: '',
  };

  attributeLabels = {
    email: i18n.t('Email'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}