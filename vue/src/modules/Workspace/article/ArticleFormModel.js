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
    Object.assign(this, {...data});
  }
}