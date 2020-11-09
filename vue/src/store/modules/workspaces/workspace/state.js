/** @var { WorkspaceState } */
const STATE = {
  showModal: false,
  workspaces: [],
  modalWorkspace: null,
  breadCrumb: [],

  view: {
    loading: false,
    workspace: {},
    articles: {
      loading: false,
      data: [],
      modal: {
        show: false,
        object: null
      }
    }
  }
};

export default STATE;
