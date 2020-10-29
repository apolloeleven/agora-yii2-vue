import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "@/shared/i18n";

export default class TimelineFormModel extends BaseModel {
  workspace_id = null;
  file = null;
  file_url = '';
  description = '';

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
    if (data.workspaceTimelinePosts) {
      data.workspace_id = data.workspaceTimelinePosts.map(w => w.workspace_id).toString();
    }
    data.created_at = data.created_at / 1000;
    data.updated_at = data.updated_at / 1000;
    Object.assign(this, {...data});
  }
}