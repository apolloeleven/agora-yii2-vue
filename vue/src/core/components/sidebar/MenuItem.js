export default class MenuItem {

  constructor(name, {
    text,
    path = '',
    weight = 0,
    icon = '',
    image = '',
    linkOptions = {},
    children = [],
    isGroup = false,
    favourite = false,
  }) {
    if (!name) {
      throw new Error("Please provide name in MenuItem constructor");
    }
    this.path = path;
    this.isGroup = isGroup;
    this.text = text;
    this.weight = weight;
    this.icon = icon;
    this.image = image;
    this.linkOptions = linkOptions;
    this.children = children.map(child => new MenuItem(child.name || child.text, child));
    this.favourite = favourite;
    this.name = name;
  }
}
