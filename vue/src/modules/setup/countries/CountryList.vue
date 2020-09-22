<template>
  <div class="customer-list page page-with-table">
    <page-header :title="$t('Countries')">
      <b-button variant="primary" @click="showCountryModal">
        <i class="fas fa-plus-circle"></i>
        {{ $t('Add new country') }}
      </b-button>
    </page-header>
    <div class="content-wrapper p-3">
      <content-spinner :show="countries.loading" :text="$t('Please wait...')" class="h-100"/>
      <div class="row page-wrapper">
        <div class="col-sm-3 page-sidebar">
          <b-list-group>
            <b-list-group-item href="javascript:void"
                               v-for="country of countries.data"
                               :key="country.id"
                               @click="selectCountry(country)"
                               class="d-flex justify-content-between"
                               :class="{active: country === selectedCountry}">
              <span>{{ country.name }}</span>
              <div class="buttons">
                <b-button variant="outline-primary" size="sm" class="mr-2" v-b-tooltip.hover :title="$t('Edit Country')"
                          @click="editCountry(data.item)">
                  <i class="fas fa-edit"></i>
                </b-button>
                <b-button variant="outline-danger" size="sm" v-b-tooltip.hover :title="$t('Delete Country')"
                          @click="onDeleteCountry(data.item)">
                  <i class="fas fa-trash-alt"></i>
                </b-button>
              </div>
            </b-list-group-item>
          </b-list-group>

        </div>
        <div class="col-sm-9 ">
          <b-tabs class="page-content border" >
            <b-tab :title="$t('Departments')" active>
              <p class="p-2 border-bottom text-right">
                <b-button variant="primary" @click="showDepartmentModal">
                  <i class="fas fa-plus-circle"></i>
                  {{ $t('Add new department') }}
                </b-button>
              </p>
              <department-list-group v-if="selectedCountry && selectedCountry.departments.length"
                                     :edit-handler="editDepartment" :delete-handler="onDeleteDepartment"
                                     :departments="selectedCountry.departments"/>
              <no-data-available v-if="selectedCountry && !selectedCountry.departments.length"
                                 :text="$t('Country does not have departments')" :height="100"/>
            </b-tab>
          </b-tabs>
        </div>
      </div>
    </div>
    <country-modal/>
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "@/core/components/ContentSpinner";
import PageHeader from "@/core/components/PageHeader";
import CountryModal from "@/modules/setup/countries/CountryModal";
import i18n from "@/shared/i18n";
import DepartmentListGroup from "@/modules/setup/departments/DepartmentListGroup";
import NoDataAvailable from "@/core/components/NoDataAvailable";

const {mapActions, mapState} = createNamespacedHelpers('setup');
export default {
  name: "CountryList",
  components: {NoDataAvailable, DepartmentListGroup, CountryModal, PageHeader, ContentSpinner},
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
  methods: {
    ...mapActions(['getCountries', 'showCountryModal', 'deleteCountry', 'showDepartmentModal', 'deleteDepartment']),
    selectCountry(country) {
      this.selectedCountry = country;
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
        }
      }
    }
  },
  mounted() {
    this.getCountries();
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