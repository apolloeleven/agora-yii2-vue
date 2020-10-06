import MenuItem from "../../../../core/components/sidebar/MenuItem";
import store from "../../../../store";
import auth from "../../../../core/services/authService";
import httpService from "../../../../core/services/httpService";
import MenuService from "../../../../core/components/sidebar/MenuService";

class FavouritesService {
  addFavourite(name, path) {
    this.addFavouriteItem(name, path);
    this.saveFavourites();
  }

  addFavouriteItem(name, path) {
    MenuService.addItem(new MenuItem(path, {
      text: name,
      path: path,
      weight: 1001,
      favourite: true,
      icon: 'fas fa-link',
      linkOptions: {
        'class': 'pl-4'
      },
    }));
  }

  removeFavourite(path) {
    MenuService.removeItem(path);
    this.saveFavourites();
  }

  inFavourites(path) {
    return store.state._menuItems[path];
  }

  saveFavourites() {
    return httpService.post(`/v1/workspaces/article/add-to-favourites`, store.getters['favourites']);
  }

  async readFromStorage() {
    if (auth.getCurrentUser()) {
      const {success, body} = await httpService.get(`/v1/workspaces/article/read-from-favourites`)
      if (success) {
        this.removeAllFavourites();
        for (let item of JSON.parse(body)) {
          this.addFavouriteItem(item.text, item.path);
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