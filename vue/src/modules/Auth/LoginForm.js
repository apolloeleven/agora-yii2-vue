import BaseModel from "../../core/components/input-widget/BaseModel";
import i18n from '../../shared/i18n';

export default class LoginForm extends BaseModel {
  username = null;
  password = null;

  rules = {
    username: [
      {rule: 'required'},
      {rule: 'regex', pattern: '^[a-zA-Z0-9]+([._@]?[a-zA-Z0-9]+)*$', message: 'This must be valid username'},
    ],
    password: 'required'
  };

  attributeLabels = {
    username: i18n.t('Username'),
    password: i18n.t('Password')
  };

  constructor(username = '', password = '') {
    super();
    this.username = username;
    this.password = password;
  }
}
