export class AppSettings {
  static url() {
    return process.env.VUE_APP_API_HOST;
  }

  static getUrl(url) {
    return this.url() + url;
  }
}
