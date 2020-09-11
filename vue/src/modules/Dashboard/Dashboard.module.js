import MenuService from "../../core/components/sidebar/MenuService";
import MenuItem from "../../core/components/sidebar/MenuItem";
import i18n from './../../shared/i18n'
import Dashboard from "./Dashboard";

MenuService.addItem(new MenuItem('/dashboard', i18n.t('Dashboard'), 1, ['fas', 'tachometer-alt']));
