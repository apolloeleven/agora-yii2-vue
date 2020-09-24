import httpService from "../../../core/services/httpService";

const invitationService = {
  url: 'v1/users/invitation',
  get() {
    return httpService.get(this.url + `?expand=createdBy,user&sort=-created_at`);
  },
  invite(email) {
    return httpService.post(this.url + "?expand=createdBy", {email});
  },
  delete(id) {
    return httpService.delete(this.url + `/${id}?expand=createdBy`);
  },
  getEmailByToken(token) {
    return httpService.get(this.url + '/get-email?token=' + token);
  }
};

export default invitationService;