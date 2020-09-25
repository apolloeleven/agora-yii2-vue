import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class EmployeeModel extends BaseModel {
  id = null;
  position = null;
  country_id = null;
  department_id = null;

  rules = {}

  attributeLabels = {};

  toJSON() {
    let data = super.toJSON();
    delete data.country_id;
    return data;
  }

  constructor() {
    super();
  }
}