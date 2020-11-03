<template>
  <ValidationObserver ref="countryModal">
    <b-modal id="country-modal"
             :title="this.model.id ? $t('Edit country') : $t('Add new country')"
             :visible="countryModal.show"
             @shown="onShown"
             @hidden="onHidden"
             @ok="onSubmit">
      <form v-on:submit.prevent="onSubmit">
        <input-widget ref="nameInputWidget" :model="model" attribute="name"/>
      </form>
    </b-modal>
  </ValidationObserver>
</template>

<script>

import {createNamespacedHelpers} from 'vuex';
import CountryModel from "@/modules/setup/countries/CountryModel";
import InputWidget from "@/core/components/input-widget/InputWidget";

const {mapState, mapActions} = createNamespacedHelpers('setup');
export default {
  name: "CountryModal",
  components: {InputWidget},
  data() {
    return {
      model: new CountryModel()
    }
  },
  computed: {
    ...mapState({
      countryModal: state => state.countryModal,
      country: state => state.countryModal.data
    }),
  },
  watch: {
    country() {
      if (this.country && this.country.id) {
        this.model = new CountryModel(this.country);
      }
    }
  },
  methods: {
    ...mapActions(['hideCountryModal', 'updateCountry', 'createCountry']),
    async onSubmit(ev) {
      ev.preventDefault();

      let response;
      if (this.model.id) {
        response = await this.updateCountry(this.model);
      } else {
        response = await this.createCountry(this.model);
      }

      if (response.success) {
        this.hideCountryModal();
      } else {
        this.model.setMultipleErrors(response.body)
      }
    },
    onShown() {
      this.$refs.nameInputWidget.focus()
    },
    onHidden() {
      this.model = new CountryModel();
      this.hideCountryModal();
    },
  }
}
</script>

<style lang="scss" scoped>

</style>
