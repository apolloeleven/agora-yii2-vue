import BaseModel from "../../../core/components/input-widget/BaseModel";
import i18n from "../../../shared/i18n";

export default class WorkspaceFormModel extends BaseModel {
  id = null;
  name = '';
  abbreviation = '';
  description = '';
  image = null;
  folder_in_folder = false;

  rules = {
    name: 'required',
    abbreviation: '',
    description: '',
    image: '',
    folder_in_folder: ''
  };

  attributeLabels = {
    name: i18n.t('Name'),
    abbreviation: i18n.t('Abbreviation'),
    description: i18n.t('Description'),
    image: i18n.t('Image'),
    folder_in_folder: i18n.t('Enable creating folders inside folders'),
  };

  constructor(data = {}) {
    super();
    data.folder_in_folder = !!data.folder_in_folder;
    Object.assign(this, {...data});
  }
}