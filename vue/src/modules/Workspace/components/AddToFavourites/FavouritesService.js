import MenuItem from "../../../../core/components/sidebar/MenuItem";
import store from "../../../../store";
import auth from "../../../../core/services/authService";
import httpService from "../../../../core/services/httpService";
import MenuService from "../../../../core/components/sidebar/MenuService";

class FavouritesService {
  /**
   * Add articles or folders into favourites
   *
   * @param name
   * @param path
   * @param icon
   */
  addFavourite(name, path, icon) {
    this.addFavouriteItem(name, path, icon);
    this.saveFavourites();
  }

  /**
   * Add each item into sidebar
   *
   * @param name
   * @param path
   * @param icon
   */
  addFavouriteItem(name, path, icon) {
    MenuService.addItem(new MenuItem(path, {
      text: name,
      path: path,
      weight: 1001,
      favourite: true,
      icon: icon,
      linkOptions: {
        'class': 'pl-4'
      },
    }));
  }

  /**
   * Remove item from favourites
   *
   * @param path
   */
  removeFavourite(path) {
    MenuService.removeItem(path);
    this.saveFavourites();
  }

  /**
   * Check if article already exist into favourites
   *
   * @param path
   * @returns {*}
   */
  inFavourites(path) {
    return store.state._menuItems[path];
  }

  /**
   * Save favourites items to db
   *
   * @returns {Promise | Promise<unknown>}
   */
  saveFavourites() {
    return httpService.post(`/v1/workspaces/article/add-to-favourites`, store.getters['favourites']);
  }

  /**
   * Get favourites short cuts from db
   *
   * @returns {Promise<void>}
   */
  async readFromStorage() {
    if (auth.getCurrentUser()) {
      const {success, body} = await httpService.get(`/v1/workspaces/article/read-from-favourites`)
      if (success) {
        this.removeAllFavourites();
        for (let item of JSON.parse(body)) {
          this.addFavouriteItem(item.text, item.path, item.icon);
        }
      }
    }
  }

  removeAllFavourites() {
    const favourites = store.getters['favourites'] || [];
    favourites.forEach(fav => {
      this.removeFavourite(fav.path);
    })
  }
}

const favouritesService = new FavouritesService();

export default favouritesService;