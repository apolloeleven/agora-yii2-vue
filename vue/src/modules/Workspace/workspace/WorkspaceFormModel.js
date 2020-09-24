import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class WorkspaceFormModel extends BaseModel {
  name = '';
  abbr = '';

  rules = {
    name: 'required',
    abbr: '',
  };

  attributeLabels = {
    name: i18n.t('Name'),
    abbr: i18n.t('Abbreviation'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}