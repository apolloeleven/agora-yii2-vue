import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class ArticleFormModel extends BaseModel {
  title = '';
  body = '';
  image = null;

  rules = {
    title: 'required',
    body: '',
    image: '',
  };

  attributeLabels = {
    title: i18n.t('Title'),
    body: i18n.t('Body'),
    image: i18n.t('Image'),
  };

  constructor(data = {}) {
    super();
    data.created_at = data.created_at / 1000;
    data.updated_at = data.updated_at / 1000;
    Object.assign(this, {...data});
  }
}