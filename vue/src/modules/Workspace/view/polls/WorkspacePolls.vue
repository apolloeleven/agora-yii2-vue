<template>
  <div v-if="loading">
    <content-spinner show/>
  </div>
  <div v-else class="workspace-polls">
    <b-card no-body class="mb-2">
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
    <div class="poll-record">
      <no-data :model="pollData" :text="$t('There are no polls')"/>
      <PollItem v-for="(item, index) in pollData" :key="`poll-item-${index}`" :item="item" :index="index"
                @onDeleteClick="onDeleteClick(item)" @onVoteClick="onVoteClick"/>
    </div>
  </div>
</template>

<script>
import ContentSpinner from "@/core/components/ContentSpinner";
import {createNamespacedHelpers} from "vuex";
import NoData from "@/core/components/NoData";
import PollItem from "./PollItem";
import InputWidget from "../../../../core/components/input-widget/InputWidget";
import PollsFormModel from "./PollsFormModel";
import AnswerModel from "./AnswerModel";
import Vue from "vue";
import {clone} from "lodash";

const {mapState: mapWorkspaceState, mapActions: mapWorkspaceActions} = createNamespacedHelpers('workspace');
export default {
  name: "WorkspacePolls",
  components: {InputWidget, PollItem, NoData, ContentSpinner},
  data() {
    return {
      model: new PollsFormModel(),
      showInputs: false,
    }
  },
  computed: {
    ...mapWorkspaceState({
      loading: state => state.view.polls.loading,
      pollData: state => state.view.polls.data,
    }),
    disabledButton() {
      return !this.model.question || !this.model.description ||
        this.model.answers.filter(a => a.answer.replace(/\s/g, '') !== '').length < 2
    },
  },
  methods: {
    ...mapWorkspaceActions(['getPolls', 'createPoll', 'deletePoll', 'addVote']),
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
      data['is_multiple'] = this.model.multipleChoice ? 1 : 0;

      const {success, body} = await this.createPoll(data);
      if (success) {
        this.$toast(this.$t(`Poll created successfully`));
        this.showInputs = false;
        this.model = new PollsFormModel();
        this.addNewAnswer(2);
      } else {
        this.$toast(body.message, 'danger');
      }
    },
    async onDeleteClick(item) {
      const result = await this.$confirm(
        this.$t(`Are you sure you want to delete this poll?`),
        this.$t('This operation can not be undone')
      );
      if (result) {
        const {success, body} = await this.deletePoll(item);
        if (success) {
          this.$toast(this.$t(`Poll deleted successfully`));
        } else {
          this.$toast(body, 'danger');
        }
      }
    },
    async onVoteClick(index) {
      const {success, body} = await this.addVote(index);
      if (success) {
        this.$toast(this.$t(`Vote added successfully`));
      } else {
        this.$toast(body, 'danger');
      }
    }
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