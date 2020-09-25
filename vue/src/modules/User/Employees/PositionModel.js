import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class EmployeeModel extends BaseModel {
  name = null;
  country_id = null;
  departments = [];

  rules = {
  }

  attributeLabels = {
  };

  constructor() {
    super();
  }
}