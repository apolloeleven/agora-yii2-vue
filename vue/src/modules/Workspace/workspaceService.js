import httpService from "../../core/services/httpService";

const workspaceService = {
  url: 'v1/workspaces/workspace',
  create(data) {
    return httpService.post(`${this.url}?expand=updatedBy`, data)
  },
  update(data) {
    return httpService.put(`${this.url}/${data.id}`, data)
  },
  delete(id) {
    return httpService.delete(`${this.url}/${id}`);
  },
  getUserWorkspaces() {
    return httpService.get(`${this.url}/get-user-workspaces?expand=updatedBy&sort=name`);
  },
};

export default workspaceService;