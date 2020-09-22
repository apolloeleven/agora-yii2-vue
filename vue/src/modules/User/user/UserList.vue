<template>
  <div id="user" class="d-flex flex-column">
    <div v-if="userList.loading" class="text-center text-info my-2 d-flex flex-column align-items-center">
      <b-spinner class="align-middle"/>
      <strong>{{ $t('Loading...') }}</strong>
    </div>
    <div v-if="!userList.loading" id="userList" class="p-3">
      <b-card>
        <div class="pb-4 pt-2 d-flex align-items-center">
          <b-form inline>
            <label class="mr-sm-2" for="per-page-select">{{ $t('Per Page:') }}</label>
            <b-select
                style="width: 70px;"
                id="per-page-select"
                v-model="perPage"
                :options="perPageOptions"
                value-field="item"
                text-field="name"
            />
          </b-form>
          <div class="pagination-table-info ml-3">
            {{
              $t(`Showing {start} - {end} records out of {total}`, {
                'start': pagingStart,
                'end': pagingEnd,
                'total': userList.total
              })
            }}
          </div>
          <div class="flex-grow-1"></div>
          <b-form @submit="onSearchUser" style="width: 350px;" class="mr-1">
            <b-input-group>
              <b-form-input
                  id="user-list-search"
                  v-model.trim="userList.keyword"
                  :placeholder="$t('Type a search keyword to filter users')"
              ></b-form-input>
              <b-input-group-append>
                <b-button type="submit" variant="info">{{ $t('Search') }}</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form>
          <b-form>
            <b-button
                aria-controls="collapse"
                variant="secondary"
                :aria-expanded="visible ? 'true' : 'false'"
                @click="visible = !visible"
            >
              <i class="fa fa-filter"></i>
              {{ $t('Advanced Filter') }}
            </b-button>
          </b-form>
        </div>
        <b-collapse id="collapse" class="mt-2" v-model="visible">
          <b-form class="d-flex">
            <div class="d-flex flex-column flex-grow-1">
              <div class="d-flex">
                <b-form-group class="mr-1 flex-grow-1">
                  <b-form-input
                      id="name-search"
                      v-model.trim="userList.name"
                      :placeholder="$t('Name')"
                  ></b-form-input>
                </b-form-group>
                <b-form-group class="mr-1 flex-grow-1">
                  <b-form-input
                      id="email-search"
                      v-model.trim="userList.email"
                      :placeholder="$t('Email')"
                  ></b-form-input>
                </b-form-group>
                <b-form-group class="mr-1 flex-grow-1">
                  <b-form-input id="phone-search" v-model.trim="userList.phone" :placeholder="$t('Phone')">
                  </b-form-input>
                </b-form-group>
              </div>
              <div class="d-flex">
                <b-form-group class="mr-1 flex-grow-1">
                  <multiselect v-model="userList.dropDown.jobTitles" :placeholder="$t('Job Title')"
                               :options="jobTitleOptions" :multiple="true"
                               :selectLabel="$t('Press enter to select')" :deselectLabel="$t('Press enter to remove')"
                               :selectedLabel="$t('Selected')" track-by="value" label="text">
                    <span slot="noOptions">{{ $t('List is empty.') }}</span>
                    <template slot="tag" slot-scope="{ option, remove }">
                      <span class="multiselect__tag">
                        <span>{{ $t(option.value) }}</span>
                        <span class="multiselect__tag-icon" @click="remove(option)"></span>
                      </span>
                    </template>
                  </multiselect>
                </b-form-group>
                <b-form-group class="mr-1 flex-grow-1">
                  <multiselect v-model="userList.dropDown.roles" :placeholder="$t('Role')" :options="roleOptions"
                               :multiple="true" :selectLabel="$t('Press enter to select')"
                               :deselectLabel="$t('Press enter to remove')"
                               :selectedLabel="$t('Selected')" track-by="value" label="text">
                    <span slot="noOptions">{{ $t('List is empty.') }}</span>
                    <template slot="tag" slot-scope="{ option, remove }">
                      <span class="multiselect__tag">
                        <span>{{ $t(option.value) }}</span>
                        <span class="multiselect__tag-icon" @click="remove(option)"></span>
                      </span>
                    </template>
                  </multiselect>
                </b-form-group>
                <b-form-group class="mr-1 flex-grow-1">
                  <multiselect v-model="userList.dropDown.countries" :placeholder="$t('Country')"
                               :options="countryOptions" :multiple="true" :selectLabel="$t('Press enter to select')"
                               :deselectLabel="$t('Press enter to remove')" :selectedLabel="$t('Selected')"
                               track-by="value" label="text">
                    <span slot="noOptions">{{ $t('List is empty.') }}</span>
                    <template slot="tag" slot-scope="{ option, remove }">
                      <span class="multiselect__tag">
                        <span>{{ $t(option.value) }}</span>
                        <span class="multiselect__tag-icon" @click="remove(option)"></span>
                      </span>
                    </template>
                  </multiselect>
                </b-form-group>
              </div>
              <div class="d-flex">
                <b-form-group class="mr-1 flex-grow-1">
                  <multiselect v-model="userList.dropDown.specialTasks" :placeholder="$t('Special Tasks')"
                               :options="specialTaskOptions" :multiple="true" :selectLabel="$t('Press enter to select')"
                               :deselectLabel="$t('Press enter to remove')" :selectedLabel="$t('Selected')"
                               track-by="value" label="text">
                    <span slot="noOptions">{{ $t('List is empty.') }}</span>
                    <template slot="tag" slot-scope="{ option, remove }">
                      <span class="multiselect__tag">
                        <span>{{ $t(option.value) }}</span>
                        <span class="multiselect__tag-icon" @click="remove(option)"></span>
                      </span>
                    </template>
                  </multiselect>
                </b-form-group>
                <b-form-group class="mr-1 flex-grow-1">
                  <multiselect v-model="userList.dropDown.expertise" :placeholder="$t('Expertise')"
                               :options="expertiseOptions" :multiple="true" :selectLabel="$t('Press enter to select')"
                               :deselectLabel="$t('Press enter to remove')" :selectedLabel="$t('Selected')"
                               track-by="value" label="text">
                    <span slot="noOptions">{{ $t('List is empty.') }}</span>
                    <template slot="tag" slot-scope="{ option, remove }">
                      <span class="multiselect__tag">
                        <span>{{ $t(option.value) }}</span>
                        <span class="multiselect__tag-icon" @click="remove(option)"></span>
                      </span>
                    </template>
                  </multiselect>
                </b-form-group>
                <b-form-group class="mr-1 flex-grow-1">
                  <multiselect v-model="userList.dropDown.language" :placeholder="$t('Languages')"
                               :options="languageOptions" :multiple="true" :selectLabel="$t('Press enter to select')"
                               :deselectLabel="$t('Press enter to remove')" :selectedLabel="$t('Selected')"
                               track-by="value" label="text">
                    <span slot="noOptions">{{ $t('List is empty.') }}</span>
                    <template slot="tag" slot-scope="{ option, remove }">
                      <span class="multiselect__tag">
                        <span>{{ $t(option.value) }}</span>
                        <span class="multiselect__tag-icon" @click="remove(option)"></span>
                      </span>
                    </template>
                  </multiselect>
                </b-form-group>
              </div>
            </div>
            <div class="d-flex flex-column justify-content-between" style="height: 84px">
              <b-button @click="onSearchUser" class="button-wrap" type="submit" variant="primary">
                <i class="fa fa-search"></i>
                {{ $t('Search') }}
              </b-button>
              <b-button @click="resetSearch" class="button-wrap" variant="secondary">
                <i class="fa fa-undo"></i>
                {{ $t('Reset') }}
              </b-button>
            </div>
          </b-form>
        </b-collapse>
        <b-table responsive small striped hover id="users-table" :items="userList.users" :fields="fields"
                 :per-page="userList.perPage" :current-page="userList.currentPage" :sort-by="sortBy"
                 :sort-desc="sortDesc" :no-local-sorting="true" @sort-changed="onUsersSort">
          <template v-slot:table-busy>
            <div class="text-center text-danger my-2">
              <b-spinner class="align-middle"></b-spinner>
              <strong>{{ $t('Loading...') }}</strong>
            </div>
          </template>
          <template v-slot:cell(avatar)="data">
            <UserViewLink :user="data.item">
              <img :src="defaultAvatar" class="user-image rounded-0">
            </UserViewLink>
          </template>
          <template v-slot:cell(name)="data">
            <UserViewLink :user="data.item" :key="'name' + data.item.id">
              {{ data.item.userProfile.first_name }} {{ data.item.userProfile.last_name }}
            </UserViewLink>
          </template>
          <template v-slot:cell(position)="data">
              <span class="badge badge-secondary mr-2" v-for="pos in data.item.userProfile.position">
                {{ pos }}
              </span>
          </template>
          <template v-slot:cell(department)="data">
              <span class="badge badge-secondary mr-2" v-for="pos in data.item.userProfile.department">
                {{ pos }}
              </span>
          </template>
          <template v-slot:cell(job_title)="data">
            <b-badge class="mr-2" v-for="job_title in getUniqueJobTitles(data.item)">
              {{ $t(job_title) }}
            </b-badge>
          </template>
          <template v-slot:cell(country)="data">
            <b-badge class="mr-2" v-for="country in getUniqueCountries(data.item)">
              {{ $t(country) }}
            </b-badge>
          </template>
          <template v-slot:cell(status)="data">
            <span class="badge" :class="{
              'badge-warning': data.item.status !== 1,
              'badge-success': data.item.status === 1,
            }">{{ data.value === 1 ? $t('Active') : $t('Disabled') }}</span>
          </template>
          <template v-slot:cell(roles)="data">
            <b-badge class="mr-2" v-for="role in (data.item.roles)">
              {{ $t(role) }}
            </b-badge>
          </template>
          <template v-slot:cell(phone)="data">
            <span :key="'phone' + data.item.id"> {{ data.item.userProfile.phone }} </span>
          </template>
          <template v-slot:cell(actions)="data">
            <b-dropdown size="lg" variant="link" toggle-class="text-decoration-none" no-caret right>
              <template v-slot:button-content>
                <i class="fa fa-ellipsis-v"></i>
              </template>
              <b-dropdown-item @click="onEditClick(data.item)">
                <i class="fa fa-pencil-alt"></i>
                {{ $t('Edit') }}
              </b-dropdown-item>
              <b-dropdown-item @click="onDeleteClick(data.item)">
                <i class="fa far-trash-alt"></i>
                {{ $t('Delete') }}
              </b-dropdown-item>
            </b-dropdown>
          </template>
        </b-table>
        <b-pagination v-if="userList.pageCount > 1" v-model="currentPage" limit="10" align="center"
                      :total-rows="userList.total" :per-page="userList.perPage" aria-controls="users-table"/>
      </b-card>
    </div>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import Multiselect from "vue-multiselect";
import UserViewLink from "./UserViewLink";

const {mapState, mapActions} = createNamespacedHelpers('user');

export default {
  name: "UserList",
  components: {
    Multiselect,
    UserViewLink
  },
  data() {
    return {
      visible: false,
      sort: null,
      perPage: 50,
      currentPage: 1,
      perPageOptions: [
        {item: 5, name: "5"},
        {item: 10, name: "10"},
        {item: 20, name: "20"},
        {item: 50, name: "50"},
        {item: 100, name: "100"}
      ],
    }
  },
  computed: {
    ...mapState(['userList', 'autoCompleteData', 'defaultAvatar']),
    ...mapState({
      currentUser: state => state.currentUser || {roles: []}
    }),
    pagingStart() {
      return this.userList.total > 0 ? this.perPage * (this.currentPage - 1) + 1 : 0;
    },
    pagingEnd() {
      return Math.min(this.perPage * this.currentPage, this.userList.total)
    },
    sortBy() {
      return this.userList.sort.replace(/^-?/, '');
    },
    sortDesc() {
      return this.userList.sort[0] === '-'
    },
    languageOptions() {
      return this.autoCompleteData.languageOptions.map(op => ({value: op, text: this.$t(op)}));
    },
    expertiseOptions() {
      return this.autoCompleteData.expertiseOptions.map(op => ({value: op, text: this.$t(op)}));
    },
    specialTaskOptions() {
      return this.autoCompleteData.specialTaskOptions.map(op => ({value: op, text: this.$t(op)}));
    },
    countryOptions() {
      return this.autoCompleteData.countryOptions.map(op => ({value: op, text: this.$t(op)}));
    },
    roleOptions() {
      return this.autoCompleteData.roleOptions.map(op => ({value: op, text: this.$t(op)}));
    },
    jobTitleOptions() {
      return this.autoCompleteData.jobTitleOptions.map(op => ({value: op, text: this.$t(op)}));
    },
    fields() {
      return [
        // {key: 'checkbox', label: ''},
        {key: 'avatar', label: this.$t('Image')},
        {key: 'name', label: this.$t('Name'), sortable: true},
        {key: 'email', label: this.$t('Email'), sortable: true},
        {key: 'phone', label: this.$t('Phone'), sortable: true},
        {key: 'job_title', label: this.$t('Job Title'), sortable: true},
        {key: 'department', label: 'Department'},
        {key: 'status', label: this.$t('Status'), sortable: true},
        {key: 'roles', label: this.$t('Role'), sortable: true},
        {key: 'country', label: this.$t('Country'), sortable: true},
        {key: 'actions', label: this.$t('Actions')},
      ];
    }
  },
  watch: {
    userList() {
      this.sort = this.userList.sort
    },
    currentPage() {
      this.getUsers();
    },
    perPage() {
      this.currentPage = 1;
      this.getUsers();
    }
  },
  methods: {
    ...mapActions(['getAll', 'delete', 'showModal', 'showUserDeleteForm', 'getDropdownOptions']),
    onUsersSort(e) {
      this.sort = (e.sortDesc ? '-' : '') + e.sortBy;
      this.getUsers();
    },
    getUsers() {
      const params = {
        perPage: this.perPage,
        page: this.currentPage,
      };
      if (this.sort) {
        params.sort = this.sort;
      }
      this.getAll(params);
    },
    onSearchUser(evt) {
      evt.preventDefault();
      this.currentPage = 1;
      this.getUsers();
    },
    onEditClick(user) {
      this.showModal(user)
    },
    onDeleteClick(user) {
      this.showUserDeleteForm(user);
    },
    getUniqueJobTitles(user) {
      const department_positions = user.userProfile.department_position || [];
      const job_titles = department_positions
          .filter(dp => !!dp.job_title)
          .map(dp => dp.job_title);
      return [...new Set(job_titles)];
    },
    getUniqueCountries(user) {
      const department_positions = user.userProfile.department_position || [];
      const countries = department_positions
          .filter(dp => !!dp.country)
          .map(dp => dp.country);
      return [...new Set(countries)];
    },
    resetSearch() {
      this.userList.keyword = '';
      this.userList.name = '';
      this.userList.email = '';
      this.userList.phone = '';
      this.userList.dropDown.specialTasks = [];
      this.userList.dropDown.expertise = [];
      this.userList.dropDown.language = [];
      this.userList.dropDown.jobTitles = [];
      this.userList.dropDown.roles = [];
      this.userList.dropDown.countries = [];
      this.getUsers();
    }
  },
  mounted() {
    this.getDropdownOptions();
    this.getUsers();
  }
}
</script>

<style lang="scss">

@import "../../../core/scss/variables";

#userList {
  .user-image {
    width: 48px;
    height: 48px;
  }

  td {
    vertical-align: middle;
  }
}

.pagination-table-info {
  font-size: 13.6px;
  font-weight: 400;
  line-height: 21px;

  span {
    font-weight: 700;
  }
}

.button-wrap {
  align-items: start;
  min-width: 90px;
}
</style>
