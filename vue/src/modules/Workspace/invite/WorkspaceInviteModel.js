import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "@/shared/i18n";

export default class WorkspaceInviteModel extends BaseModel {
  allUser = [];

  rules = {};

  attributeLabels = {
    allUser: i18n.t('Select all registered users'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}