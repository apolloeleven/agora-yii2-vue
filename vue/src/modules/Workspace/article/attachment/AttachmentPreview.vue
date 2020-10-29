<template>
  <b-modal v-if="fileIndex" modal-class="article-file-preview" :visible="previewModal.show" size="lg"
           ref="attachmentModal" id="article-dialog" scrollable hide-footer @hidden="hidePreviewModal"
           :title="fileIndex.label || fileIndex.name">
    <div class="bg-light text-right bg-secondary shadow-sm py-2 px-3">
      <div class="attachment-toolbar mb-3">
        <AttachmentDownloadButton tag="button" :file="fileIndex" class="attachment-button"/>
        <AttachmentDeleteButton tag="button" :fileIds="[fileIndex.id]" :model="model" class="attachment-button"/>
      </div>

      <div class="attachment-preview-control">
        <b-button variant="outline-info" @click="prev">
          {{ $t('Previous') }}
        </b-button>
        <b-button variant="outline-info" @click="next">
          {{ $t('Next') }}
        </b-button>
      </div>
    </div>

    <b-alert class="mobile-alert" variant="warning" :show="true">
      {{ $t('This functionality is not available on mobile devices') }}
    </b-alert>
    <div class="h-100">
      <b-carousel
        id="carousel-1" no-animation :value="previewModal.activeFile"
        :interval="0" ref="attachmentPreview" img-width="10" img-height="1" @sliding-start="onCarouselChange">
        <b-carousel-slide v-for="file in previewModal.files">
          <template>
            <template v-if="fileService.isImage(file.path)">
              <img class="attachment-img" :src="file.path" alt="image slot">
            </template>

            <template v-if="fileService.isPdf(file)">
              <iframe class="d-block w-100 h-100" :src="file.path"></iframe>
            </template>

            <template v-if="fileService.isAudio(file)">
              <audio controls>
                <source :src="file.path" type="audio/mpeg">
              </audio>
            </template>

            <template v-if="fileService.isVideo(file.path)">
              <video controls class="attachment-video">
                <source :src="file.path" type="video/mp4">
                <source :src="file.path" type="video/ogg">
              </video>
            </template>
            <template v-if="file.mime === 'no-attachment'">
              <div class="no-attachment"> {{ $t('No Attachments Found') }}</div>
            </template>

            <template v-if="!fileService.mimeTypes().includes(file.mime)">
              <div class="no-attachment"> {{ $t('Preview is not available') }}</div>
              <AttachmentDownloadButton
                v-if="previewModal.files[0].mime !== 'no-attachment'" tag="button" :file="fileIndex"
                class="attachment-button not-available-download">
              </AttachmentDownloadButton>
            </template>
          </template>
        </b-carousel-slide>
      </b-carousel>
    </div>
  </b-modal>
</template>

<script>
import AttachmentDownloadButton from "./AttachmentDownloadButton";
import AttachmentDeleteButton from "./AttachmentDeleteButton";
import {createNamespacedHelpers} from "vuex";
import fileService from "./AttachmentFileService";

const {mapState, mapActions} = createNamespacedHelpers('article');

export default {
  name: "AttachmentPreview",
  components: {AttachmentDeleteButton, AttachmentDownloadButton},
  props: {
    model: Object,
  },
  data() {
    return {
      fileService: fileService,
    }
  },
  computed: {
    ...mapState(['previewModal']),
    fileIndex() {
      return this.previewModal.files[this.previewModal.activeFile]
    }
  },
  methods: {
    ...mapActions(['hidePreviewModal', 'changeCarousel']),
    prev() {
      this.$refs.attachmentPreview.prev()
    },
    next() {
      this.$refs.attachmentPreview.next()
    },
    onCarouselChange(ev) {
      this.changeCarousel(ev);
      let media = document.getElementsByClassName('audio-video'),
        i = media.length;
      while (i--) {
        media[i].pause();
      }
    },
  },
}
</script>

<style lang="scss">

.article-file-preview {

  .modal-open .modal {
    overflow-y: hidden;
  }

  .modal-body {
    display: flex;
    flex-direction: column;
    padding: 0;
  }

  .attachment-button {
    margin-left: 10px;
    padding: 5px;
  }

  .not-available-download {
    margin: 5px 5px;
  }

  .modal-dialog-scrollable {
    height: 100%;

    .modal-content {
      height: 100%;
      max-height: unset;
      padding-bottom: 16px;
    }

    .modal-body {
      overflow-y: unset;
    }

    #carousel-1 {
      height: 100%;
      width: 100%;

      @media (max-width: 365px) {
        & {
          padding-top: 52%;
        }
      }
      @media only screen and (max-width: 404px) and (min-width: 365px) {
        & {
          padding-top: 45%;
        }
      }
      @media only screen and (max-width: 500px) and (min-width: 404px) {
        & {
          padding-top: 35%;
        }
      }
      @media only screen and (max-width: 576px) and (min-width: 500px) {
        & {
          padding-top: 30%;
        }
      }

      .carousel-inner {
        height: 100%;

        .carousel-item {
          height: 100%;
        }
      }
    }
  }

  .carousel-caption {
    position: absolute;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 10;
    padding-top: 0;
    padding-bottom: 0;
    color: #fff;
    text-align: center;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }

  .carousel-indicators {
    background-color: rgba(0, 0, 0, 0.4);
    margin: 0;
  }

  .attachment-img {
    width: auto;
    max-width: 100%;
    max-height: 100%;
  }

  .attachment-video {
    width: auto;
    height: auto;
    max-height: 100%;
    max-width: 100%;
    margin-bottom: 45px;
    z-index: 10000000;
  }

  .attachment-toolbar {
    display: flex;
    justify-content: flex-end;
    z-index: 1000000;
  }

  .no-attachment {
    color: #b6b6b6;
    font-weight: bold;
    text-shadow: 1px 1px 0 #FFFFFF;
    font-size: 2rem;
  }

  @media (min-height: 400px) {
    .modal-lg,
    .modal-xl {
      max-width: 95%;
    }
  }

  .mobile-alert {
    display: none;
  }

  @media (max-width: 576px) {
    .mobile-alert {
      display: block;
    }
  }

  .attachment-preview-control {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    z-index: 101;
  }
}

</style>