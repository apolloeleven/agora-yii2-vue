import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "@/shared/i18n";

export default class WorkspaceInviteModel extends BaseModel {
  name = '';

  rules = {
    name: 'required',
  };

  attributeLabels = {
    name: i18n.t('Name'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}