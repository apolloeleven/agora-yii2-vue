const fileService = {
  TYPES: {},
  TYPE_VIDEO: 'far fa-file-video',
  TYPE_IMAGE: 'far fa-file-image',
  TYPE_WORD: 'far fa-file-word',
  TYPE_PDF: 'far fa-file-pdf',
  TYPE_EXCEL: 'far fa-file-excel',
  TYPE_POWERPOINT: 'far fa-file-powerpoint',

  getTypes() {
    return {
      'jpg': this.TYPE_IMAGE,
      'png': this.TYPE_IMAGE,
      'jpeg': this.TYPE_IMAGE,
      'svg': this.TYPE_IMAGE,
      'webp' : this.TYPE_IMAGE,

      'odt': this.TYPE_WORD,
      'doc': this.TYPE_WORD,
      'docx': this.TYPE_WORD,

      'xls': this.TYPE_EXCEL,
      'xlsx': this.TYPE_EXCEL,
      'xltm': this.TYPE_EXCEL,

      'ppt': this.TYPE_POWERPOINT,
      'pptx': this.TYPE_POWERPOINT,

      'pdf': this.TYPE_PDF,

      'mp4': this.TYPE_VIDEO,
      'avi': this.TYPE_VIDEO,
      'webm': this.TYPE_VIDEO,
      'ogg': this.TYPE_VIDEO,
      'wmv': this.TYPE_VIDEO,
      'mov': this.TYPE_VIDEO,
    }
  },
  mimeTypes() {
    return [
      'image/png',
      'application/pdf',
      'audio/mp3',
      'video',
      'image/jpeg',
      'image/jpg',
      'image/webp',
      'no-attachment'
    ]
  },
  isImage(path) {
    const ext = path.substring(path.lastIndexOf('.') + 1).toLowerCase();
    return this.getTypes()[ext] === this.TYPE_IMAGE;
  },
  isAudio(file) {
    return file.mime === 'audio/mp3';
  },
  isVideo(path) {
    const ext = path.substring(path.lastIndexOf('.') + 1);
    return ext.toLowerCase() === 'mp4';
  },
  isPdf(file) {
    return file.mime === 'application/pdf';
  },
};

export default fileService;