import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from '../../../shared/i18n';

export default class UserProfileForm extends BaseModel {
  first_name = '';
  last_name = '';
  job_title = '';
  country = [];
  department = [];
  phone = '';
  mobile = '';

  rules = {
    first_name: 'required',
    last_name: 'required',
    country: '',
    phone: '',
    mobile: '',
  };

  attributeLabels = {
    first_name: i18n.t('First Name'),
    last_name: i18n.t('Last Name'),
    job_title: i18n.t('Position'),
    country: i18n.t('Country'),
    phone: i18n.t('Phone'),
    mobile: i18n.t('Mobile')
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}
