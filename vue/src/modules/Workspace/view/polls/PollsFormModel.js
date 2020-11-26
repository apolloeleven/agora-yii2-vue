import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "../../../../shared/i18n";

export default class PollsFormModel extends BaseModel {
  question = '';
  description = '';
  answers = [];
  postTimeline = false;
  multipleChoice = false;

  attributeLabels = {
    question: i18n.t('Question'),
    description: i18n.t('Description'),
    postTimeline: i18n.t('Post on the timeline'),
    multipleChoice: i18n.t('Multiple answers per user'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}