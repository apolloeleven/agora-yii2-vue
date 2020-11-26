<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <b-card v-else no-body class="workspace-polls">
    <b-card-body>
      <input-widget
        :model="model" attribute="question" :label="false" :placeholder="$t('Question')" @click="onInputClick">
      </input-widget>
      <input-widget v-if="showInputs" :model="model" attribute="description" type="richtext"/>
      <label v-text="$t('Answers')" v-if="showInputs"/>
      <div v-if="showInputs" v-for="(answer, index) in model.answers" :key="`poll-answers-${index}`">
        <input-widget
          :label="false" :model="answer" attribute="answer" :append="`<i class='fas fa-trash-alt'/>`"
          :placeholder="$t('Add answer...')" @onButtonClick="removeAnswer(index)">
        </input-widget>
      </div>
      <input-widget
        v-if="showInputs" :label="false" :model="model" attribute="addAnswer" :append="`<i class='fas fa-plus'/>`"
        :placeholder="$t('Add answer...')" @onButtonClick="addNewAnswer">
      </input-widget>
      <input-widget v-if="showInputs" :model="model" attribute="postTimeline" type="checkbox"/>
      <input-widget v-if="showInputs" :model="model" attribute="multipleChoice" type="checkbox"/>
    </b-card-body>
    <b-card-footer v-if="showInputs">
      <b-button variant="primary" class="float-right">
        {{ $t('Save') }}
      </b-button>
    </b-card-footer>
  </b-card>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import {createNamespacedHelpers} from "vuex";
import PollsFormModel from "./PollsFormModel";
import InputWidget from "@/core/components/input-widget/InputWidget";
import AnswerModel from "./AnswerModel";
import Vue from "vue";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');
export default {
  name: "WorkspacePolls",
  components: {InputWidget, ContentSpinner},
  data() {
    return {
      model: new PollsFormModel(),
      answerModel: new AnswerModel(),
      showInputs: false,
    }
  },
  computed: {
    ...mapWorkspaceState({
      loading: state => state.view.polls.loading,
    }),
  },
  methods: {
    onInputClick() {
      this.showInputs = true;
    },
    addNewAnswer: function () {
      this.model.answers.push(new AnswerModel());
    },
    removeAnswer: function (index) {
      Vue.delete(this.model.answers, index);
    },
  },
  mounted() {
    this.addNewAnswer();
  }
}
</script>

<style lang="scss" scoped>
.workspace-polls {
  width: 680px;
  margin: 0 auto;
}
</style>