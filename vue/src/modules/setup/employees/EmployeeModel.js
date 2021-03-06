import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";
import RoleModel from "@/modules/setup/employees/RoleModel";
import UserDepartmentModel from "@/modules/setup/employees/UserDepartmentModel";

export default class EmployeeModel extends BaseModel {
  id = null;
  first_name = null;
  last_name = null;
  mobile = null;
  phone = null;
  birthday = null;
  hobbies = null;
  email = null;
  userWorkspaces = [];
  status = false;
  userDepartments = [];

  rules = {
    first_name: 'required',
    last_name: 'required',
    email: [
      {rule: 'required'},
      {rule: 'email'},
    ],
  }

  attributeLabels = {
    email: i18n.t('Email'),
    first_name: i18n.t('First Name'),
    last_name: i18n.t('Last Name'),
    mobile: i18n.t('Mobile'),
    phone: i18n.t('Phone'),
    birthday: i18n.t('Birthday'),
    hobbies: i18n.t('Hobbies'),
    status: i18n.t('Activate User'),
  };

  constructor(data = {}) {
    super();
    const userDepartments = [];

    if (data.userDepartments) {
      for (let userDepartment of data.userDepartments) {
        userDepartments.push(new UserDepartmentModel({
          id: userDepartment.id,
          position: userDepartment.position,
          country_id: userDepartment.department.country_id,
          department_id: userDepartment.department.id
        }))
      }
    }
    const userWorkspaces = [];
    if (data.userWorkspaces) {
      for (let userWorkspace of data.userWorkspaces) {
        userWorkspaces.push(new RoleModel({
          id: userWorkspace.id,
          role: userWorkspace.role,
          workspace_id: userWorkspace.workspace_id,
        }))
      }
    }
    data.userDepartments = userDepartments;
    data.userWorkspaces = userWorkspaces;
    Object.assign(this, {...data});
  }
}