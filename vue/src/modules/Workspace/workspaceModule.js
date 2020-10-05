import MenuService from "../../core/components/sidebar/MenuService";
import MenuItem from "../../core/components/sidebar/MenuItem";
import i18n from './../../shared/i18n'

MenuService.addItem(new MenuItem('workspace', {
  text: i18n.t('My Workspaces'),
  path: '/workspace',
  weight: 100,
  icon: 'fas fa-home',
}));
