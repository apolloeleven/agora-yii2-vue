import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class WorkspaceFormModel extends BaseModel {
  name = '';
  abbr = '';
  description = '';

  rules = {
    name: 'required',
    abbr: '',
    description: '',
  };

  attributeLabels = {
    name: i18n.t('Name'),
    abbr: i18n.t('Abbreviation'),
    description: i18n.t('Description'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}