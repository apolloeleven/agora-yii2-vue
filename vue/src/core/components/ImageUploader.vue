<template>
  <div class="file-uploader">
    <div class="file-preview-ctr">
      <div :class="{'complete-drag':computedSrc ,'file-drag-ctr':fileDragClass}"
           :placeholder="$t('Drop Image here.')"
           ref="fileDrag"
           @dragover="onFileDrag"
           @dragleave="onFileDragLeave"
           @drop="onFileDrop">
      </div>
      <img class="image-preview" v-if="computedSrc" :src="computedSrc" ref="img">
      <avatar-cropper
        v-if="showCropper"
        ref="cropper"
        trigger=".pick-avatar"
        :labels="cropperLabels"
        :uploadHandler="uploadHandler"
        :cropper-options="cropperOptions"
      />
    </div>
    <div class="action-button mt-1">
      <b-button class="pick-avatar btn-choose mr-3" variant="primary">
        <input type="file" @change="onFileChoose" ref="fileInput">
        {{$t('Choose image')}}
      </b-button>
      <b-button @click="onRemoveClick" class="btn-remove" variant="danger">
        {{$t('Remove')}}
      </b-button>
    </div>

  </div>
</template>

<script>
import AvatarCropper from "vue-avatar-cropper"

export default {
  name: "ImageUploader",
  components: {AvatarCropper},
  props: {
    src: String
  },
  data() {
    return {
      showCropper: true,
      imageRemoved: false,
      imageSrc: null,
      fileDragClass: true,
      cropperLabels: {
        submit: this.$t("Submit"), cancel: this.$t("Cancel")
      },
      cropperOptions: {
        aspectRatio: 1,
        autoCropArea: 0.5,
        viewMode: 1,
        movable: true,
        zoomable: true,
      }
    }
  },
  computed: {
    computedSrc() {
      if (this.imageRemoved) {
        return '';
      }
      return this.imageSrc || this.src;
    }
  },
  methods: {
    uploadHandler(cropper) {
      this.$refs.fileInput.value = '';
      this.imageSrc = '';
      this.imageRemoved = false;
      this.imageSrc = cropper.getCroppedCanvas().toDataURL();
      cropper.getCroppedCanvas().toBlob(blob => {
        this.$emit('input', new File([blob], "avatar.png"))
      });
    },
    onFileChoose(ev) {
      this.imageSrc = '';
      this.imageRemoved = false;
      const reader = new FileReader();
      reader.onload = () => {
        this.imageSrc = reader.result;
      };
      if (!this.$refs.fileDrag.classList.contains('complete-drag')) {
        this.$refs.fileDrag.classList.add('complete-drag');
      }
      reader.readAsDataURL(ev.target.files[0]);
      this.$emit('input', ev.target.files[0]);
    },
    onRemoveClick() {
      this.imageSrc = '';
      this.imageRemoved = true;
      if (this.$refs.fileDrag.classList.contains('complete-drag')) {
        this.$refs.fileDrag.classList.remove('complete-drag');
      }
      this.$refs.fileInput.value = '';
      this.$emit('input', '');
    },
    onFileDrag(ev) {
      this.$refs.fileDrag.classList.add('active-drag');
      if (this.$refs.fileDrag.classList.contains('complete-drag')) {
        this.$refs.fileDrag.classList.remove('complete-drag');
      }
      ev.preventDefault();
    },
    onFileDrop(ev) {
      this.imageSrc = '';
      this.imageRemoved = false;
      const reader = new FileReader();

      if (this.showCropper) {
        const cropperVue = this.$refs.cropper;
        reader.onload = () => {
          cropperVue.dataUrl = reader.result;
        };
        reader.readAsDataURL(ev.dataTransfer.files[0]);
        cropperVue.filename = ev.dataTransfer.files[0].name || 'unknown';
        cropperVue.mimeType = this.mimeType || ev.dataTransfer.files[0].type;
        cropperVue.$emit('changed', ev.dataTransfer.files[0], reader);
      } else {
        reader.onload = () => {
          this.imageSrc = reader.result;
        };
        reader.readAsDataURL(ev.dataTransfer.files[0]);
        this.$emit('input', ev.dataTransfer.files[0]);
      }

      if (this.$refs.fileDrag.classList.contains('active-drag')) {
        this.$refs.fileDrag.classList.remove('active-drag');
      }
      this.$refs.fileDrag.classList.add('complete-drag');
      ev.preventDefault();
    },
    onFileDragLeave() {
      if (this.$refs.fileDrag.classList.contains('active-drag')) {
        this.$refs.fileDrag.classList.remove('active-drag');
      }
    }
  }
}

</script>


<style lang="scss" scoped>
$dragColor: #737373;
.file-preview-ctr {
  background-color: #efefef;
  min-width: 250px;
  min-height: 250px;
  display: inline-flex;
  align-items: center;
  position: relative;

  .file-drag-ctr {
    border: 1px dashed #737373;
    z-index: 3;
    width: 100%;
    height: 100%;
    position: absolute;

    &:before {
      content: attr(placeholder);
      position: relative;
      display: flex;
      width: 100%;
      height: 100%;
      cursor: default;
      justify-content: center;
      text-align: center;
      color: #737373;
      align-items: center;
    }

    &.active-drag {
      border-color: #008BCA;
      background-color: #fff;

      &:before {
        color: #008BCA;
      }
    }

    &.complete-drag {
      &:before {
        display: none;
      }
    }
  }

  .image-preview {
    max-width: 250px;
    z-index: 2;
  }
}

.btn-choose {
  position: relative;
  overflow: hidden;

  input {
    position: absolute;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    z-index: 2;
    opacity: 0;
  }
}
</style>
<style>

.avatar-cropper .avatar-cropper-container {
  width: 70vw;
  height: 70vh;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-direction: column;
  position: relative;
  background: transparent;
}

.avatar-cropper .avatar-cropper-container .avatar-cropper-footer {
  width: 100%;
  background-color: #fff;
}

.cropper-container {
  width: 100%;
  height: 100%;
}

.avatar-cropper .avatar-cropper-container .avatar-cropper-image-container {
  width: 100%;
  max-width: 100%;
  height: 100%;
}

.avatar-cropper .avatar-cropper-mark {
  background: rgba(0, 0, 0, 0.5);
}

</style>