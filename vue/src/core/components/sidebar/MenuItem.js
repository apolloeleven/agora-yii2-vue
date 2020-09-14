export default class MenuItem {

  constructor({
                text,
                path = '',
                weight = 0,
                icon = '',
                linkOptions = {},
                children = [],
                isGroup = false,
              }) {
    this.path = path;
    this.isGroup = isGroup;
    this.text = text;
    this.weight = weight;
    this.icon = icon;
    this.linkOptions = linkOptions;
    this.children = children;
    this.name = null;
  }
}
