import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class ArticleFormModel extends BaseModel {
  title = '';
  body = '';
  workspace_id = null;
  image = null;

  rules = {
    title: 'required',
    body: '',
    workspace_id: 'required',
    image: '',
  };

  attributeLabels = {
    title: i18n.t('Title'),
    body: i18n.t('Body'),
    workspace_id: i18n.t('Workspace ID'),
    image: i18n.t('Image'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}