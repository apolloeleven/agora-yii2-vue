import httpService from "../../core/services/httpService";

const workspaceService = {
  url: 'v1/workspaces/workspace',
  create(data) {
    return httpService.post(`${this.url}?expand=updatedBy`, data)
  },
  // update(data) {
  //   data.append('_method', 'PUT');
  //   return httpService.post(`${this.url}/${id}`, data)
  // },
};

export default workspaceService;