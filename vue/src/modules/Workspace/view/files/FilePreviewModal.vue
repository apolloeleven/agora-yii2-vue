<template>
  <b-modal v-if="fileIndex" modal-class="file-preview" :visible="previewModal.show" size="lg" class="pb-0 mb-0"
           :title="fileIndex.name" ref="attachmentModal" id="file-dialog" scrollable @hidden="hidePreviewModal"
           body-class="pb-0">
    <div class="h-100">
      <b-carousel
        id="carousel-1" no-animation :value="previewModal.activeFile" :interval="0" ref="filePreview" img-width="10"
        img-height="1" @sliding-start="onCarouselChange">
        <b-carousel-slide v-for="file in previewModal.files" v-bind:key="`file-preview-${file.id}`">
          <template>
            <template v-if="fileService.isImage(file.url)">
              <img class="attachment-img" :src="file.url" alt="image slot">
            </template>

            <template v-if="fileService.isPdf(file)">
              <iframe class="d-block w-100 h-100" :src="file.url"/>
            </template>

            <template v-if="fileService.isAudio(file)">
              <audio controls>
                <source :src="file.path" type="audio/mpeg">
              </audio>
            </template>

            <template v-if="fileService.isVideo(file.url)">
              <video controls class="attachment-video">
                <source :src="file.url" type="video/mp4">
                <source :src="file.url" type="video/ogg">
              </video>
            </template>

            <template v-if="!fileService.mimeTypes().includes(file.mime)">
              <div class="no-attachment">{{ $t('Preview is not available') }}</div>
            </template>
          </template>
        </b-carousel-slide>
      </b-carousel>
    </div>
    <template #modal-footer>
      <div class="d-flex w-100 align-items-center">
        <div class="mr-auto">
          <b-button @click="onDownload(fileIndex)" variant="primary">
            <i class="fas fa-download"/>
            {{ $t('Download') }}
          </b-button>
        </div>
        <div>
          <b-button variant="info" @click="prev" class="mr-2">
            {{ $t('Previous') }}
          </b-button>
          <b-button variant="info" @click="next">
            {{ $t('Next') }}
          </b-button>
        </div>
      </div>
    </template>
  </b-modal>
</template>

<script>
import {createNamespacedHelpers} from "vuex";
import fileService from "../../../../core/services/fileService";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');
export default {
  name: "FilePreviewModal",
  props: {
    model: Object,
  },
  data() {
    return {
      fileService: fileService,
    }
  },
  computed: {
    ...mapWorkspaceState({
      previewModal: state => state.view.folders.previewModal,
    }),
    fileIndex() {
      return this.previewModal.files[this.previewModal.activeFile]
    }
  },
  methods: {
    ...mapWorkspaceActions(['hidePreviewModal', 'changeCarousel']),
    prev() {
      this.$refs.filePreview.prev()
    },
    next() {
      this.$refs.filePreview.next()
    },
    onCarouselChange(e) {
      this.changeCarousel(e);
      let media = document.getElementsByClassName('audio-video'),
        i = media.length;
      while (i--) {
        media[i].pause();
      }
    },
    onDownload(item) {
      this.$emit('onDownloadClick', item)
    },
  },
}
</script>

<style lang="scss">
.file-preview {
  .modal-open .modal {
    overflow-y: hidden;
  }

  .modal-body {
    display: flex;
    flex-direction: column;
    padding: 0;
  }

  .modal-dialog-scrollable {
    height: 100%;

    .modal-content {
      height: 100%;
      max-height: unset;
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

  .attachment-preview-control {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    z-index: 101;
  }
}
</style>