import {SET_ALL, SHOW_INVITATION_MODAL, HIDE_MODAL, CHANGE_LOADING} from '@/store/modules/invitation/mutation-types';

export default {
  [SET_ALL]: (state, {rows}) => {
    state.data.rows = rows;
  },
  [SHOW_INVITATION_MODAL]: (state) => {
    state.showModal = true;
    state.modalInvitation = {};
  },
  [HIDE_MODAL]: (state) => {
    state.showModal = false;
    state.modalUser = {};
  },
  [CHANGE_LOADING]: state => state.loading = !state.loading
}