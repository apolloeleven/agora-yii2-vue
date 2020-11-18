import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "../../../../shared/i18n";

export default class ArticleFormModel extends BaseModel {
  id = null;
  title = '';
  workspace_id = null;
  body = '';

  rules = {
    title: 'required',
    body: '',
  };

  attributeLabels = {
    title: i18n.t('Title'),
    body: i18n.t('Body'),
  };

  constructor(data = {}) {
    super();
    data.created_at = data.created_at / 1000;
    data.updated_at = data.updated_at / 1000;
    Object.assign(this, {...data});
  }
}