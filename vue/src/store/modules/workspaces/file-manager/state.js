/** @var { fileManagerState } */
import i18n from "@/shared/i18n";

const STATE = {
  loading: false,
  showModal: false,
  modalFolder: null,
  foldersAndFiles: [],
  breadCrumb: [],
  currentFolder: {},
  isFile: false,
  attachConfig: {},
  file: {
    showModal: false,
    file: null,
  },

  visibleColumns: [
    {
      key: 'size',
      label: i18n.t('Size'),
      class: 'size',
      sortable: true,
      visible: false
    },
    {
      key: 'updated_at',
      label: i18n.t('Updated at'),
      sortable: true,
      visible: false
    },
    {
      key: 'updatedBy.displayName',
      label: i18n.t('Updated By'),
      sortable: true,
      visible: false
    },
  ],
};

export default STATE;
