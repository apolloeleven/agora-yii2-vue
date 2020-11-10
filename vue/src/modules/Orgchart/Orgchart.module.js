import MenuService from "../../core/components/sidebar/MenuService";
import MenuItem from "../../core/components/sidebar/MenuItem";
import i18n from './../../shared/i18n'

MenuService.addItem(new MenuItem('orgchart', {
  text: i18n.t('Org Chart'),
  path: '/orgchart',
  weight: 999,
  icon: 'fas fa-sitemap'
}));
