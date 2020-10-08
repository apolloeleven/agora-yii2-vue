import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "@/shared/i18n";

export default class DepartmentModel extends BaseModel {
  name = null;
  country_id = null;
  parent_id = null;

  rules = {
    name: 'required',
    country_id: 'required',
  };

  attributeLabels = {
    name: i18n.t('Department Name'),
    country_id: i18n.t('Country'),
    parent_id: i18n.t('Parent Department'),
  };

  constructor(data = {}) {
    super();
    Object.assign(this, {...data});
  }

  toJSON() {
    return {
      name: this.name,
      country_id: this.country_id,
      parent_id: this.parent_id,
    }
  }
}