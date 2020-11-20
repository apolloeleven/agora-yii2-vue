export default class Pagination {
  currentPage = 1;
  totalRows = null;
  perPage = null;
  data = [];
  currentData = [];

  constructor(data, perPage = 5) {
    this.totalRows = data.length;
    this.data = data;
    this.perPage = perPage
    this.selectData();
  }

  selectData() {
    let page = this.currentPage;

    let left = (page - 1) * this.perPage;
    let right = Math.min(this.totalRows, left + this.perPage);
    this.currentData = this.data.slice(left, right);
  }

  getData() {
    return this.currentData;
  }

  hasOnePage() {
    return this.pages() < 2;
  }

  pages() {
    return Math.ceil(this.totalRows / this.perPage);
  }
}