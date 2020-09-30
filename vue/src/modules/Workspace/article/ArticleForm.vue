<template>
  <ValidationObserver ref="form" v-slot="{ handleSubmit, invalid ,reset}">
    <b-modal
      :visible="showModal" id="article-form" ref="modal" size="lg"
      title="Article"
      @hidden="hideArticleModal" @ok.prevent="handleSubmit(onSubmit)" :ok-title="$t('Submit')" scrollable>
      <b-form @submit.prevent="handleSubmit(onSubmit)" novalidate>
        <b-form-group :label="$t('Upload Image')">
          <input :model="model.image" type="file"/>
        </b-form-group>
        <input-widget :model="model" attribute="title"></input-widget>
        <input-widget :model="model" attribute="body" type="richtext"></input-widget>
      </b-form>
    </b-modal>
  </ValidationObserver>
</template>

<script>
import InputWidget from "../../../core/components/input-widget/InputWidget";
import ArticleFormModel from "./ArticleFormModel";
import {createNamespacedHelpers} from "vuex";

const {mapState, mapActions} = createNamespacedHelpers('article');

export default {
  name: "ArticleForm",
  components: {InputWidget},
  data() {
    return {
      model: new ArticleFormModel(),
    }
  },
  computed: {
    ...mapState(['showModal'])
  },
  methods: {
    ...mapActions(['hideArticleModal']),
    async onSubmit() {

    }
  }
}
</script>

<style scoped>

</style>