<template>
  <v-container>
    <v-radio-group v-model="selectedSearchType" row>
      <v-radio label="Pesquisar por Nome ou CPF" value="nameOrCpf"></v-radio>
      <v-radio label="Pesquisar por ID" value="id"></v-radio>
      <v-radio label="Pesquisar por Localidade" value="location"></v-radio>
    </v-radio-group>

    <v-form v-if="selectedSearchType === 'nameOrCpf'" @submit.prevent="searchPerson">
      <v-text-field v-model="searchTerm" label="Pesquisar por Nome ou CPF"></v-text-field>
      <v-btn type="submit" color="primary">Pesquisar</v-btn>
    </v-form>

    <v-form v-if="selectedSearchType === 'id'" @submit.prevent="searchById">
      <v-text-field v-model="searchId" label="Pesquisar por ID"></v-text-field>
      <v-btn type="submit" color="primary">Pesquisar</v-btn>
    </v-form>

    <v-btn v-if="selectedSearchType === 'location'" @click="openSearchModal" color="primary">Pesquisar por Localização</v-btn>

    <v-dialog v-model="isSearchModalVisible" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Pesquisar por Localização</span>
        </v-card-title>
        <v-card-text>
          <v-form @submit.prevent="searchByLocation">
            <v-select
              v-model="locationFilters.state"
              :items="states"
              label="Estado"
              required
            ></v-select>
            <v-select
              v-model="locationFilters.city"
              :items="cities"
              label="Cidade"
              :disabled="!locationFilters.state"
            ></v-select>
            <v-text-field
              v-model="locationFilters.neighborhood"
              label="Bairro"
              :disabled="!locationFilters.city"
            ></v-text-field>
            <v-btn type="submit" color="primary">Pesquisar</v-btn>
            <v-btn @click="isSearchModalVisible = false" color="secondary">Cancelar</v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </v-dialog>
    
    <v-list v-if="results.length">
      <v-list-item v-for="person in results" :key="person.id" @click="selectPerson(person)">
        <v-list-item-title>{{ person.name }}</v-list-item-title>
        <v-list-item-subtitle>{{ person.cpf }}</v-list-item-subtitle>
      </v-list-item>
    </v-list>
  </v-container>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      searchTerm: '',
      searchId: '',
      results: [],
      selectedSearchType: 'nameOrCpf',
      isSearchModalVisible: false,
      locationFilters: {
        state: '',
        city: '',
        neighborhood: ''
      },
      states: [],
      cities: []
    };
  },
  created() {
    this.fetchStates();
  },
  watch: {
    'locationFilters.state': function(newVal, oldVal) {
      this.fetchCities();
    }
  },
  methods: {
    async fetchStates() {
      try {
        const response = await axios.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados');
        this.states = response.data.map(state => state.sigla);
      } catch (error) {
        console.error('Erro ao buscar estados:', error);
      }
    },
    async fetchCities() {
      if (!this.locationFilters.state) {
        return;
      }
      try {
        const response = await axios.get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${this.locationFilters.state}/municipios`);
        this.cities = response.data.map(city => city.nome);
      } catch (error) {
        console.error('Erro ao buscar cidades:', error);
      }
    },
    async searchPerson() {
      try {
        const response = await axios.get('/people', {
          params: {
            query: this.searchTerm,
          },
        });
        this.results = response.data;
        this.$emit('people-found', this.results);
      } catch (error) {
        console.error(error);
        this.$swal.fire('Erro', 'Erro ao pesquisar pessoa.', 'error');
      }
    },
    async searchById() {
      try {
        const response = await axios.get(`/people/${this.searchId}`);
        this.results = [response.data];
        this.$emit('people-found', this.results);
      } catch (error) {
        console.error(error);
        this.$swal.fire('Erro', 'Erro ao pesquisar pessoa por ID.', 'error');
      }
    },
    async searchByLocation() {
      try {
        const params = {};
        if (this.locationFilters.state) {
          params.state = this.locationFilters.state;
        }
        if (this.locationFilters.city) {
          params.city = this.locationFilters.city;
        }
        if (this.locationFilters.neighborhood) {
          params.neighborhood = this.locationFilters.neighborhood;
        }

        const response = await axios.get('/people', { params });
        this.results = response.data;
        this.isSearchModalVisible = false;

        if (this.locationFilters.state && !this.locationFilters.city && !this.locationFilters.neighborhood) {
          this.$emit('update-search-message', `Pessoas do estado ${this.locationFilters.state}`);
        } else if (this.locationFilters.state && this.locationFilters.city && !this.locationFilters.neighborhood) {
          this.$emit('update-search-message', `Pessoas da cidade ${this.locationFilters.city}, estado ${this.locationFilters.state}`);
        } else if (this.locationFilters.state && this.locationFilters.city && this.locationFilters.neighborhood) {
          this.$emit('update-search-message', `Pessoas do bairro ${this.locationFilters.neighborhood}, cidade ${this.locationFilters.city}, estado ${this.locationFilters.state}`);
        }
      } catch (error) {
        console.error('Erro ao buscar pessoas:', error);
        this.$swal.fire('Erro', 'Erro ao buscar pessoas.', 'error');
      }
    },
    selectPerson(person) {
      this.$emit('view-person', person);
    },
    openSearchModal() {
      this.isSearchModalVisible = true;
    }
  },
};
</script>
