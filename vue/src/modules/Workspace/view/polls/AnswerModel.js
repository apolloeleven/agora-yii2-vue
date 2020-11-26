import BaseModel from "@/core/components/input-widget/BaseModel";

export default class AnswerModel extends BaseModel {
  answer = '';

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}