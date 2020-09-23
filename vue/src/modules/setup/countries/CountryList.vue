<template>
  <div class="customer-list page page-with-table">
    <page-header :title="$t('Countries')">
      <b-button variant="primary" class="mr-2" @click="showCountryModal">
        <i class="fas fa-plus-circle"></i>
        {{ $t('Add new country') }}
      </b-button>
      <b-button variant="outline-primary" class="mr-2" :disabled="!selectedCountry"
                @click="editCountry(selectedCountry)">
        <i class="fas fa-edit"></i> {{ $t('Edit Country') }}
      </b-button>
      <b-button variant="outline-danger" class="mr-2" :disabled="!selectedCountry"
                @click="onDeleteCountry(selectedCountry)">
        <i class="fas fa-trash-alt"></i> {{ $t('Delete Country') }}
      </b-button>
    </page-header>
    <div class="content-wrapper p-3">
      <content-spinner :show="countries.loading" :text="$t('Please wait...')" class="h-100"/>
      <div class="row page-wrapper" v-if="!countries.loading && countries.loaded">
        <div class="col-sm-3 page-sidebar">
          <b-list-group>
            <b-list-group-item v-for="country of countries.data"
                               :key="country.id"
                               @click="selectCountry(country)"
                               class="d-flex justify-content-between hover-pointer"
                               :class="{active: country === selectedCountry}">
              <span>{{ country.name }}</span>
            </b-list-group-item>
          </b-list-group>

        </div>
        <div class="col-sm-9">
          <div class="page-content p-2">
            <b-tabs>
              <b-tab :title="$t('Departments')" active>
                <div>
                  <p class="p-2 text-right mb-0">
                    <b-button :disabled="!selectedCountry" size="sm" variant="primary" @click="showDepartmentModal">
                      <i class="fas fa-plus-circle"></i>
                      {{ $t('Add new department') }}
                    </b-button>
                  </p>
                  <department-list-group v-if="selectedCountry && selectedCountry.departmentsTree.length"
                                         :edit-handler="editDepartment"
                                         :delete-handler="onDeleteDepartment"
                                         :departments="selectedCountry.departmentsTree"/>
                </div>
                <no-data-available v-if="selectedCountry && !selectedCountry.departmentsTree.length"
                                   :text="$t('Country does not have departments')" :height="100"/>
              </b-tab>
            </b-tabs>
          </div>
        </div>
      </div>
    </div>
    <country-modal/>

    <department-modal v-if="selectedCountry" :country="selectedCountry"/>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "@/core/components/ContentSpinner";
import PageHeader from "@/core/components/PageHeader";
import CountryModal from "@/modules/setup/countries/CountryModal";
import i18n from "@/shared/i18n";
import DepartmentListGroup from "@/modules/setup/countries/departments/DepartmentListGroup";
import NoDataAvailable from "@/core/components/NoDataAvailable";
import DepartmentModal from "@/modules/setup/countries/departments/DepartmentModal";

const {mapActions, mapState} = createNamespacedHelpers('setup');
export default {
  name: "CountryList",
  components: {DepartmentModal, NoDataAvailable, DepartmentListGroup, CountryModal, PageHeader, ContentSpinner},
  data() {
    return {
      selectedCountry: null,
      fields: [
        'name',
        'created_at',
        'createdBy',
        'actions'
      ],
    }
  },
  computed: {
    ...mapState(['countries']),
  },
  watch: {
    countries: {
      deep: true,
      handler() {
        if (this.selectedCountry) {
          this.selectedCountry = this.countries.data.find(c => c.id === this.selectedCountry.id);
        }
      }
    }
  },
  methods: {
    ...mapActions([
      'getCountries',
      'showCountryModal',
      'deleteCountry',
      'showDepartmentModal',
      'deleteDepartment',
      'getCountryDepartments'
    ]),
    selectCountry(country) {
      this.selectedCountry = country;
    },
    selectFirstCountry() {
      if (this.countries.data.length > 0) {
        this.selectCountry(this.countries.data[0]);
      }
    },
    editCountry(country) {
      this.showCountryModal(country)
    },
    async onDeleteCountry(country) {
      const result = await this.$confirm(i18n.t(`Are you sure you want to delete that country?`))
      if (result) {
        const {success, body} = await this.deleteCountry(country.id)
        if (!success) {
          this.$alert(i18n.t(body.message || i18n.t('There was some problem. Please try again in several minutes...')))
        } else {
          this.selectFirstCountry();
        }
      }
    },
    editDepartment(department) {
      this.showDepartmentModal(department)
    },
    async onDeleteDepartment(department) {
      const result = await this.$confirm(i18n.t(`Are you sure you want to delete that department?`))
      if (result) {
        const {success, body} = await this.deleteDepartment(department.id)
        if (!success) {
          this.$alert(i18n.t(body.message || i18n.t('There was some problem. Please try again in several minutes...')))
        } else {
          this.getCountryDepartments(department.country_id)
        }
      }
    }
  },
  async mounted() {
    let response = await this.getCountries();

    if (response.success) {
      this.selectFirstCountry();
    }
  }
}
</script>

<style lang="scss" scoped>
.page-wrapper {
  display: flex;
  margin-left: -10px;
  margin-right: -10px;
}

.page-sidebar {
  width: 300px;
  padding-left: 10px;
  padding-right: 10px;
}

.page-content {
  flex: 1;
  height: 100%;
  background-color: #FFF;
}
</style>
