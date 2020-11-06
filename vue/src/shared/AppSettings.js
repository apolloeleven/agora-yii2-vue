export class AppSettings {
  static url() {
    return process.env.VUE_APP_API_HOST;
  }

  static getUrl(url) {
    return this.url() + url;
  }

  static getTwitterFeedUrl() {
    return process.env.VUE_APP_TWITTER_FEED_URL;
  }
}
