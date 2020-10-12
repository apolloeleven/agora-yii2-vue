import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "@/shared/i18n";

export default class TimelineFormModel extends BaseModel {
  workspace = '';
  file = null;
  description = '';

  rules = {
    workspace: [
      {rule: 'required'}
    ]
  }

  attributeLabels = {
    workspace: i18n.t('Workspace'),
    file: i18n.t('Image or Video'),
    description: i18n.t('Description'),
  }

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}