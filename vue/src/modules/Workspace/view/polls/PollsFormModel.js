import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "../../../../shared/i18n";

export default class PollsFormModel extends BaseModel {
  question = '';
  description = '';

  rules = {
    question: 'required',
    description: 'required',
  };

  attributeLabels = {
    question: i18n.t('Question'),
    description: i18n.t('Description'),
  };

  constructor(data = {}) {
    super();
    data.created_at = data.created_at / 1000;
    data.updated_at = data.updated_at / 1000;
    Object.assign(this, {...data});
  }
}