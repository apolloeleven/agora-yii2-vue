<template>
  <b-modal :visible="readOnlyForm.showModal" size="xl" id="user-read-only-information-form" ref="modal"
           :static="true" :lazy="false" ok-only scrollable :ok-title="$t('Close')" @show="showModal"
           @hidden="hideModal" :title="$t('Profile')" :hide-footer="true" no-enforce-focus>
    <template v-if="readOnlyForm.user.id">
      <div class="row">
        <div class="col-sm-12 col-lg-6">
          <div class="card card-information mb-4">
            <div class="card-header">
              <h5 class="m-0">{{ $t('Information') }}</h5>
            </div>
            <div class="card-body">
              <div class="mb-3 text-center">
                <b-img :src="defaultAvatar" width="128" height="128" :alt="readOnlyForm.user.displayName"/>
              </div>
              <dl class="row m-0">
                <dt class="col-sm-4">{{ $t('First Name') }}</dt>
                <dd class="col-sm-8">{{ readOnlyForm.user.userProfile.first_name }}</dd>
                <dt class="col-sm-4">{{ $t('Last Name') }}</dt>
                <dd class="col-sm-8">{{ readOnlyForm.user.userProfile.last_name }}</dd>
                <dt class="col-sm-4">{{ $t('Email') }}</dt>
                <dd class="col-sm-8">{{ readOnlyForm.user.email }}</dd>
                <dt class="col-sm-4">{{ $t('Phone') }}</dt>
                <dd class="col-sm-8">{{ readOnlyForm.user.userProfile.phone }}</dd>
                <dt class="col-sm-4">{{ $t('Mobile') }}</dt>
                <dd class="col-sm-8">{{ readOnlyForm.user.userProfile.mobile }}</dd>
                <dt class="col-sm-4">{{ $t('Birthday') }}</dt>
                <dd class="col-sm-8">{{ readOnlyForm.user.userProfile.birthday }}</dd>
                <dt class="col-sm-4">{{ $t('Special Tasks') }}</dt>
                <dd class="col-sm-8">
                  <b-badge class="mr-1"
                           v-for="(task, index) of readOnlyForm.user.userProfile.special_tasks" :key="index">
                    {{ $t(task) }}
                  </b-badge>
                </dd>
                <dt class="col-sm-4">{{ $t('Country') }}</dt>
                <dd class="col-sm-8">{{ readOnlyForm.user.userProfile.country }}</dd>
                <dt class="col-sm-4">{{ $t('Languages') }}</dt>
                <dd class="col-sm-8">
                  <b-badge class="mr-1"
                           v-for="(language, index) of readOnlyForm.user.userProfile.languages" :key="index">
                    {{ $t(language) }}
                  </b-badge>
                </dd>
                <dt class="col-sm-4">{{ $t('Free Text') }}</dt>
                <dd class="col-sm-8">
                  <blockquote v-html="readOnlyForm.user.userProfile.about_me">
                  </blockquote>
                </dd>
              </dl>
            </div>
          </div>
        </div>

        <div class="col-sm-12 col-lg-6">
          <div class="card card-work mb-4">
            <div class="card-header">
              <h5 class="m-0">{{ $t('Work') }}</h5>
            </div>
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-6">{{ $t('Expertise') }}</dt>
                <dd class="col-sm-6">
                  <b-badge class="mr-1"
                           v-for="(expertise, index) of readOnlyForm.user.userProfile.expertise" :key="index">
                    {{ $t(expertise) }}
                  </b-badge>
                </dd>
                <dt class="col-sm-6">{{ $t('Area Director') }}</dt>
                <dd class="col-sm-6">{{ readOnlyForm.user.userProfile.area_director }}</dd>
                <dt class="col-sm-3">{{ $t('Job Title') }}</dt>
                <dd class="col-sm-9">
                  <template v-for="(job_title, index) in distinctJobTitles">
                    <b-badge class="mr-2" :key="index">{{ $t(job_title) }}</b-badge>
                  </template>
                </dd>
                <template v-for="(dp, index) in readOnlyForm.user.userProfile.department_position">
                  <dt class="col-sm-3">{{ $t('Position') }}</dt>
                  <dd class="col-sm-9">
                    <small>{{ $t(getPosition(dp.position)) }}</small>
                    <span v-if="dp.department"> -
                      <b-badge class="mr-1"
                               v-for="(department, index) of dp.department" :key="index">
                          {{ $t(department) }}
                      </b-badge>
                    </span>
                  </dd>
                </template>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </template>

  </b-modal>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';

const {mapState, mapActions} = createNamespacedHelpers('user');

export default {
  name: "UserReadOnlyInformation",
  computed: {
    ...mapState({
      readOnlyForm: state => state.readOnlyForm
    }),
    ...mapState(['autoCompleteData', 'defaultAvatar']),
    distinctJobTitles() {
      const job_titles = (this.readOnlyForm.user.userProfile.department_position || [])
          .filter(dp => !!dp.job_title)
          .map(dp => dp.job_title);
      return [...new Set(job_titles)];
    }
  },
  methods: {
    ...mapActions(["closeForm"]),
    showModal() {

    },
    hideModal() {
      this.closeForm();
    },
    getPosition(key) {
      for (let option of this.autoCompleteData.positionOptions) {
        if (option.key === key) {
          return option.value;
        }
      }
    }
  },
}
</script>

<style lang="scss" scoped>
blockquote {
  border-left: 4px solid #e1e1e1;
  font-style: italic;
  padding: 5px 10px;
  margin-bottom: 0;

  & /deep/ p:last-child {
    margin-bottom: 0;
  }
}

dl {
  dt {
    margin-bottom: 10px;
  }
}
</style>
