import MenuService from "../../core/components/sidebar/MenuService";
import MenuItem from "../../core/components/sidebar/MenuItem";
import i18n from './../../shared/i18n'

MenuService.addItem(new MenuItem('dashboard', {
  text: i18n.t('Dashboard'),
  path: '/dashboard',
  weight: 1,
  icon: 'fas fa-tachometer-alt'
}));
