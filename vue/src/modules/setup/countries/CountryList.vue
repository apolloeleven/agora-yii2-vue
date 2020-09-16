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
      <b-card v-if="countries.loaded" no-body>
        <b-table striped hover :items="countries.data" :fields="fields" class="mb-0">
          <template v-slot:cell(created_at)="data">
            {{ data.item.created_at | toDatetime }}
          </template>
          <template v-slot:cell(createdBy)="data">
            {{ data.item.createdBy.email }}
          </template>
          <template v-slot:cell(actions)="data">
            <b-button variant="outline-primary" size="sm" class="mr-2" v-b-tooltip.hover :title="$t('Edit Country')">
              <i class="fas fa-edit"></i>
            </b-button>
            <b-button variant="outline-danger" size="sm" v-b-tooltip.hover :title="$t('Delete Country')">
              <i class="fas fa-trash-alt"></i>
            </b-button>
          </template>
        </b-table>
      </b-card>
    </div>
    <country-modal />
  </div>
</template>

<script>
import {createNamespacedHelpers} from 'vuex';
import ContentSpinner from "@/core/components/ContentSpinner";
import PageHeader from "@/core/components/PageHeader";
import CountryModal from "@/modules/setup/countries/CountryModal";

const {mapActions, mapState} = createNamespacedHelpers('setup');
export default {
  name: "CountryList",
  components: {CountryModal, PageHeader, ContentSpinner},
  data() {
    return {
      fields: [
        'name',
        'created_at',
        'createdBy',
        'actions'
      ],
    }
  },
  computed: {
    ...mapState(['countries'])
  },
  methods: {
    ...mapActions(['getCountries', 'showCountryModal'])
  },
  mounted() {
    this.getCountries();
  }
}
</script>

<style scoped>

</style>