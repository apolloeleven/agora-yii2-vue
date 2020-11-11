/** @var { WorkspaceState } */
const STATE = {
  showModal: false,
  workspaces: [],
  loading: false,
  modalWorkspace: null,

  view: {
    loading: false,
    workspace: {},
    articles: {
      loading: false,
      data: [],
      modal: {
        show: false,
        object: null
      },
      view: {
        article: null,
        loading: false,
      },
    },
    timeline: {
      loading: false,
      data: [],
      modal: {
        loading: false,
        show: false,
        object: null,
      }
    },
  }
};

export default STATE;
