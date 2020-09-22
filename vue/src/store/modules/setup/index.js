import * as actions from './actions';
import mutations from './mutations';
import state from './state';
import _ from 'lodash';

export default {
  namespaced: true,
  getters: {
    departmentsTree: (state) => {
      let departments = _.cloneDeep(state.departments.data);
      let nodes = [];
      const tree = [];

      for (let department of departments) {
        const id = department.id;
        const parentId = department.parent_id;
        nodes[id] = department;

        if (nodes[parentId]) {
          nodes[parentId].children.push(department);
        } else {
          tree.push(department);
        }
      }

      return tree;
    }
  },
  mutations,
  actions,
  state,
};
