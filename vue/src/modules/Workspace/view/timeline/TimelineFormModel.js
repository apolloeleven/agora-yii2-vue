import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "@/shared/i18n";

export default class TimelineFormModel extends BaseModel {
  workspace_id = null;
  article_id = null;
  attachment_ids = [] || null;
  file = null;
  file_url = '';
  description = '';
  action = null;

  rules = {
    workspace: [
      {rule: 'required'}
    ]
  }

  attributeLabels = {
    file: i18n.t('Select Image or Video File'),
    description: i18n.t('Description'),
  }

  constructor(data = {}) {
    super();
    data.created_at = data.created_at / 1000;
    data.updated_at = data.updated_at / 1000;
    Object.assign(this, {...data});
  }
}