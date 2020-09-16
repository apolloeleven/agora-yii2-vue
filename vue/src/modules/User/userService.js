import httpService from "../../core/services/httpService";

const userService = {
  /**
   * Action name
   */
  url: '/my-user',

  /**
   * Get all user data
   *
   * @param keyword
   * @param name
   * @param email
   * @param phone
   * @param dropDown
   * @param perPage
   * @param page
   * @param sort
   * @returns {Promise | Promise<unknown>}
   */
  get({keyword = '', name = '', email = '', phone = '', dropDown, perPage, page, sort}) {
    if (!sort) {
      sort = '-created_at';
    }
    return httpService.get(this.url + `?keyword=${keyword}&name=${name}&email=${email}&phone=${phone}&per-page=${perPage}&page=${page}&expand=userProfile,updatedBy`, {
      params: dropDown
    });
  },

  /**
   * Get data for edit each user
   *
   * @param id
   * @returns {Promise | Promise<unknown>}
   */
  getById(id) {
    return httpService.get(`${this.url}/${id}?expand=userProfile`);
  },

  /**
   * Create new user
   *
   * @param data
   * @returns {Promise | Promise<unknown>}
   */
  create(data) {
    data = this.prepareData(data);
    return httpService.post(`${this.url}?expand=userProfile,updatedBy`, data);
  },

  /**
   * Update user
   *
   * @param data
   * @returns {Promise<any>}
   */
  update(data) {
    const id = data.id;
    data = this.prepareData(data);
    let promise;
    if (data instanceof FormData) {
      data.append('_method', 'PUT');
      promise = httpService.post(`${this.url}/${id}`, data);
    } else {
      promise = httpService.put(`${this.url}/${id}`, data)
    }
    return promise
  },

  /**
   * Delete user
   *
   * @param payload
   * @returns {Promise | Promise<unknown>}
   */
  delete(payload) {
    return httpService.delete(`${this.url}/${payload.userId}?selectedUserId=${payload.selectedId}`)
  },

  /**
   * Prepare data for backend
   *
   * @param data
   * @returns {*|FormData}
   */
  prepareData(data) {
    data = toFormData(data);
    return data;
  },
};

if (window.FormData) {
  var toFormData = function (obj, form, namespace) {
    let fd = form || new FormData();
    let formKey;

    for (let property in obj) {
      if (obj.hasOwnProperty(property) && obj[property]) {
        if (namespace) {
          formKey = namespace + '[' + property + ']';
        } else {
          formKey = property;
        }

        if (obj[property] instanceof Date) {
          fd.append(formKey, obj[property].toISOString());
        } else if (typeof obj[property] === 'object' && !(obj[property] instanceof File)) {
          toFormData(obj[property], fd, formKey);
        } else {
          fd.append(formKey, obj[property]);
        }
      }
    }

    return fd;
  }
}

export default userService;