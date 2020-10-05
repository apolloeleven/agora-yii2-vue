import MenuItem from "../../../../core/components/sidebar/MenuItem";
import store from "../../../../store";
import auth from "../../../../core/services/authService";
import httpService from "../../../../core/services/httpService";
import MenuService from "../../../../core/components/sidebar/MenuService";

class FavouritesService {
  addFavourite(name, path) {
    this._addFavourite(name, path);
    this.saveFavourites();
  }

  _addFavourite(name, path) {
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
    console.log(store.state._menuItems[path])
    return store.state._menuItems[path];
  }

  saveFavourites() {
    const favourites = store.getters['favourites'];
    return httpService.post(`/v1/workspaces/article/add-to-favourites`, favourites);
  }

  readFromStorage() {
    const user = auth.getCurrentUser();
    if (user) {
      httpService.get(`/v1/workspaces/article/read-from-favourites`)
        .then(response => {
          this.removeAllFavourites();
          let data = JSON.parse(response.body);
          for (let item of data) {
            this._addFavourite(item.text, item.path);
          }
        });
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