import BaseModel from "@/core/components/input-widget/BaseModel";
import i18n from "@/shared/i18n";

export default class CountryModel extends BaseModel {
  name = null;
  password = null;

  rules = {
    name: 'required'
  };

  attributeLabels = {
    name: i18n.t('Country Name'),
  };
}