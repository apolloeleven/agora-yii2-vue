import MenuItem from './MenuItem';

class MenuService {
  /**
   * @type MenuItem[]
   */
  menuItems = [];

  addItem(menuItem) {
    if (!(menuItem instanceof MenuItem)) {
      throw new Error("addMenuItem accepts MenuItem class instance only")
    }
    this.menuItems.push(menuItem);
  }

  getItem(path) {
    return this.menuItems.find(item => item.path === path);
  }

  getItems() {
    return this.menuItems.sort((a, b) => a.weight - b.weight);
  }

}


export default new MenuService();
