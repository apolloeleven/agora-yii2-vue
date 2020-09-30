import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class EmployeeModel extends BaseModel {
  id = null;
  position = null;
  country_id = null;
  department_id = null;

  rules = {
    id: [
      {rule: 'required'}
    ],
    position: [
      {rule: 'required'}
    ],
    country_id: [
      {rule: 'required'}
    ],
    department_id: [
      {rule: 'required'}
    ],
  }

  attributeLabels = {
    id: ' ',
    position: 'Position',
    country_id: 'Country',
    department_id: 'Department',
  };

  toJSON() {
    let data = super.toJSON();
    //TODO: discuss how to remove extra field without creating bug on initialisation in EmployeeModel.js
    // delete data.country_id;
    return data;
  }

  constructor(data) {
    super();
    Object.assign(this, {...data})
  }
}