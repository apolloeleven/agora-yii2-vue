import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class RoleModel extends BaseModel {
  id = null;
  role = '';
  workspace_id = null;

  rules = {
    id: [
      {rule: 'required'}
    ],
    role: [
      {rule: 'required'}
    ],
    workspace_id: [
      {rule: 'required'}
    ]
  }

  attributeLabels = {
    role: i18n.t('Role'),
    workspace_id: i18n.t('Workspace'),
  };

  constructor(data) {
    super();
    Object.assign(this, {...data})
  }
}