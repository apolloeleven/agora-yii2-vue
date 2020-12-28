import BaseModel from "../../core/components/input-widget/BaseModel";
import i18n from "../../shared/i18n";

export default class WorkspaceFormModel extends BaseModel {
  name = '';
  description = '';
  image = null;

  rules = {
    name: 'required',
  };

  attributeLabels = {
    name: i18n.t('Name'),
    description: i18n.t('Description'),
    image: i18n.t('Upload Image'),
  };

  constructor(data = {}) {
    super();
    if (data.created_at > 1e10){
      data.created_at = data.created_at / 1000;
      data.updated_at = data.updated_at / 1000;
    }
    Object.assign(this, {...data});
  }
}