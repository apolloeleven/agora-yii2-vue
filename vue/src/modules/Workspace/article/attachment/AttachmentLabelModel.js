import BaseModel from "../../../../core/components/input-widget/BaseModel";
import i18n from "../../../../shared/i18n";

export default class AttachmentLabelModel extends BaseModel {
  label = '';

  rules = {
    label: '',
  };

  attributeLabels = {
    label: i18n.t('Label'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}