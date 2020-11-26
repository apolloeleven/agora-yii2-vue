import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "../../../../shared/i18n";
import AnswerModel from "./AnswerModel";

export default class PollsFormModel extends BaseModel {
  question = '';
  description = '';
  answers = [];
  postTimeline = false;
  multipleChoice = false;

  rules = {
    question: 'required',
    description: 'required',
  };

  attributeLabels = {
    question: i18n.t('Question'),
    description: i18n.t('Description'),
    postTimeline: i18n.t('Post on the timeline'),
    multipleChoice: i18n.t('Multiple answers per user'),
  };

  constructor(data = {}) {
    super();
    data.created_at = data.created_at / 1000;
    data.updated_at = data.updated_at / 1000;
    Object.assign(this, {...data});
  }
}