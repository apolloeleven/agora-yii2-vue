import httpService from "../../../core/services/httpService";

const employeeService = {
  url: 'v1/setup/employee',

  get(params = {
    sort: '-created_at',
    expand: 'userWorkspaces,userDepartments,userDepartments.department,userDepartments.department.country'
  }) {
    return httpService.get(this.url, {params});
  },
  getByWorkspace(workspaceId) {
    return httpService.get(this.url+'/by-workspace', {params: {workspaceId}});
  },
  getModalDropdownData(params = {expand: 'departments'}) {
    return httpService.get(this.url + '/get-dropdown', {params})
  },
  updateUserData(params) {
    return httpService.put(this.url + `/${params.id}`, params)
  }
}

export default employeeService;