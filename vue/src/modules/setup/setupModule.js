import MenuService from "../../core/components/sidebar/MenuService";
import MenuItem from "../../core/components/sidebar/MenuItem";
import i18n from './../../shared/i18n'

MenuService.addItem(new MenuItem('setup', {
  text: i18n.t('Setup'),
  weight: 900,
  isGroup: true
}));
MenuService.addItem(new MenuItem('setup.countries', {
  text: i18n.t('Countries'),
  path: '/setup/countries',
  weight: 910,
  icon: 'fas fa-globe-americas',
}));
MenuService.addItem(new MenuItem('setup.invitations', {
  text: i18n.t('Invitations'),
  path: '/setup/invitations',
  weight: 920,
  icon: 'fas fa-envelope-open-text',
}));
MenuService.addItem(new MenuItem('setup.users', {
  text: i18n.t('Users'),
  path: '/setup/users',
  weight: 930,
  icon: 'fas fa-users',
}));
