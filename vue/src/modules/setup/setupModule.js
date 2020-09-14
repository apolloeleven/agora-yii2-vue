import MenuService from "../../core/components/sidebar/MenuService";
import MenuItem from "../../core/components/sidebar/MenuItem";
import i18n from './../../shared/i18n'

MenuService.addItem(new MenuItem({
  text: i18n.t('Setup'),
  path: '/setup',
  weight: 1000,
  icon: 'fas fa-cog'
}));
