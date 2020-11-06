/** @var { ArticleState } */
import i18n from "@/shared/i18n";

const STATE = {
  loading: false,
  showModal: false,
  modalArticle: null,
  articles: [],
  breadCrumb: [],
  currentArticle: {
    articleComments: [],
  },
  isArticle: false,
  articleFiles: [],
  attachConfig: {},
  articleFile: {
    showModal: false,
    file: null,
  },
  previewModal: {
    files: [],
    activeFile: null,
    show: false,
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
