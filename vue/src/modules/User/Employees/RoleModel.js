import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class RoleModel extends BaseModel {
  name = null;

  rules = {
    name: [
      {rule:'required'}
    ]
  }

  attributeLabels = {
    name: ' '
  };

  constructor(data) {
    super();
    Object.assign(this, {...data})
  }
}