import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class EmployeeModel extends BaseModel {
  id = null;
  first_name = null;
  last_name = null;
  email = null;
  roles = []
  userDepartments = [];

  rules = {
    first_name: [
      {rule: 'required'},
    ],
    last_name: [
      {rule: 'required'}
    ],
    email: [
      {rule: 'required'},
      {rule: 'email'},
    ],
  }

  attributeLabels = {
    email: i18n.t('Email'),
    first_name: i18n.t('First Name'),
    last_name: i18n.t('Last Name'),
  };

  toJSON() {
    let data = super.toJSON();
    return data;
  }

  constructor(data = {}) {
    super();

    const userDepartments = [];

    if (data.userDepartments) {
      for (let userDepartment of data.userDepartments) {
        userDepartments.push({
          id: userDepartment.id,
          country_id: userDepartment.department.country_id,
          department_id: userDepartment.department.id,
          position: userDepartment.position
        });
      }
    }
    const roles = [];
    if (data.roles) {
      for (let role in data.roles) {
        if (data.roles.hasOwnProperty(role)) {
          roles.push({
            name: role
          })
        }
      }
    }
    console.log(roles);
    data.userDepartments = userDepartments;
    data.roles = roles;
    Object.assign(this, {...data});
  }
}