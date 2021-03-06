import BaseModel from "../../../../core/components/input-widget/BaseModel";
import i18n from "../../../../shared/i18n";

export default class FolderFormModel extends BaseModel {
  name = '';

  rules = {
    name: 'required',
  };

  attributeLabels = {
    name: i18n.t('Name'),
  };

  constructor(data = {}) {
    super();
    data.created_at = data.created_at / 1000;
    data.updated_at = data.updated_at / 1000;
    Object.assign(this, {...data});
  }
}