/** @var { WorkspaceState } */
const STATE = {
  loading: false,
  showModal: false,
  workspaces: [],
  modalWorkspace: null,
  breadCrumb: [],
  currentWorkspace: {},
  employees: [],

  view: {
    articles: {
      loading: false,
      data: []
    }
  }
};

export default STATE;
