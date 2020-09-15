export class AppSettings {
  static url() {
    if (process.env.NODE_ENV === 'development') {
      return process.env.VUE_APP_API_HOST;
    } else if (process.env.NODE_ENV === 'production') {
      return 'http://0.0.0.0:8000';
    } else if (process.env.NODE_ENV === 'test') {
      return 'http://0.0.0.0:8000';
    }
    return null;
  }

  static getUrl(url) {
    return this.url() + url;
  }
}
