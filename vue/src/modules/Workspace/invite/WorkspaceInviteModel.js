import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "@/shared/i18n";

export default class WorkspaceInviteModel extends BaseModel {
  selectedUsers = [];
  allUser = false;
  role = 'user';

  rules = {};

  attributeLabels = {
    selectedUsers: i18n.t('Users'),
    allUser: i18n.t('Select all registered users'),
    role: i18n.t('Role'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }
}