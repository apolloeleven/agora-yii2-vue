import httpService from "../../../core/services/httpService";

const employeeService = {
  url: 'v1/users/employee',

  get(params = {
    sort: '-created_at',
    expand: 'userDepartments, userDepartments.department, userDepartments.department.country'
  }) {
    return httpService.get(this.url, {params});
  },
  getModalDropdownData(params = {expand: 'departments'}) {
    return httpService.get(this.url + '/get-dropdown', {params})
  },
  updateUserData(params) {
    return httpService.put(this.url + '/update-user-data', params)
  }
}

export default employeeService;