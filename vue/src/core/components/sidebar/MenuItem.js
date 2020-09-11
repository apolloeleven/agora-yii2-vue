export default class MenuItem {
  path = '';
  text = '';
  icon = '';
  weight = 0;

  constructor(path, text, weight = 0, icon = '', children = []) {
    this.path = path;
    this.text = text;
    this.weight = weight;
    this.icon = icon;
    this.children = children;
  }
}
