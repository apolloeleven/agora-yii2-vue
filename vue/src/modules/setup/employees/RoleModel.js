import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class RoleModel extends BaseModel {
  role = '';
  workspace = null;

  rules = {
    role: [
      {rule: 'required'}
    ],
    workspace: [
      {rule: 'required'}
    ]
  }

  attributeLabels = {
    role: i18n.t('Role'),
    workspace: i18n.t('Workspace'),
  };

  constructor(data) {
    super();
    Object.assign(this, {...data})
  }
}