import MenuItem from './MenuItem';
import store from '../../../store';

class MenuService {

  addItem(menuItem) {
    if (!(menuItem instanceof MenuItem)) {
      throw new Error("addMenuItem accepts MenuItem class instance only")
    }
    store.dispatch('addMenuItem', {
      name: menuItem.name,
      menuItem
    });
  }


  removeItem(name) {
    store.dispatch('removeMenuItem', name);
  }

  getItems() {
    return store.getters['menuItems']
  }
}

const menuService = new MenuService();

export default menuService;
