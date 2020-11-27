<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <b-card v-else no-body class="workspace-polls">
    <b-card-body>
      <input-widget
        :model="model" attribute="question" :label="false" :placeholder="$t('Question')" @click="onInputClick">
      </input-widget>
      <div v-if="showInputs">
        <input-widget :model="model" attribute="description" type="richtext"/>
        <label v-text="$t('Answers')"/>
        <div v-for="(answer, index) in model.answers" :key="`poll-answers-${index}`">
          <input-widget
            :label="false" :model="answer" attribute="answer" :placeholder="$t('Add answer...')"
            :append="!model.answers[index + 1] ? `<i class='fas fa-plus'/>` : `<i class='fas fa-trash-alt'/>`"
            @onButtonClick="onButtonClick(index)">
          </input-widget>
        </div>
        <input-widget :model="model" attribute="postTimeline" type="checkbox"/>
        <input-widget :model="model" attribute="multipleChoice" type="checkbox"/>
      </div>
    </b-card-body>
    <b-card-footer v-if="showInputs">
      <b-button variant="primary" class="float-right" @click="onSubmit" :disabled="disabledButton">
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
import {clone} from "lodash";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');
export default {
  name: "WorkspacePolls",
  components: {InputWidget, ContentSpinner},
  data() {
    return {
      model: new PollsFormModel(),
      showInputs: false,
    }
  },
  computed: {
    ...mapWorkspaceState({
      loading: state => state.view.polls.loading,
    }),
    disabledButton() {
      return !this.model.question || !this.model.description ||
        this.model.answers.filter(a => a.answer.replace(/\s/g, '') !== '').length < 2
    },
  },
  methods: {
    ...mapWorkspaceActions(['getPolls', 'createPoll']),
    onInputClick() {
      this.showInputs = true;
    },
    addNewAnswer(inputNum) {
      for (let i = 0; i < inputNum; i++) {
        this.model.answers.push(new AnswerModel());
      }
    },
    onButtonClick(index) {
      if (!this.model.answers[index + 1]) {
        this.addNewAnswer(1);
      } else {
        Vue.delete(this.model.answers, index);
      }
    },
    async onSubmit() {
      let data = clone(this.model);
      data['workspace_id'] = this.$route.params.id;
      data['answers'] = this.model.answers.filter(a => a.answer.replace(/\s/g, '') !== '').map(a => a.answer);

      const {success, body} = await this.createPoll(data);
      if (success) {
        this.$toast(this.$t(`Poll Created successfully`));
        this.showInputs = false;
        this.model = new PollsFormModel();
        this.addNewAnswer(2);
      } else {
        this.$toast(body.message, 'danger');
      }
    },
  },
  mounted() {
    this.addNewAnswer(2);
    this.getPolls(this.$route.params.id);
  }
}
</script>

<style lang="scss" scoped>
.workspace-polls {
  width: 680px;
  margin: 0 auto;
}
</style>