<template>
  <v-container>
    <v-form @submit.prevent="searchPerson">
      <v-text-field v-model="searchTerm" label="Pesquisar por Nome ou CPF"></v-text-field>
      <v-btn type="submit" color="primary">Pesquisar</v-btn>
    </v-form>
    <v-divider class="my-4"></v-divider>
    <v-form @submit.prevent="searchById">
      <v-text-field v-model="searchId" label="Pesquisar por ID"></v-text-field>
      <v-btn type="submit" color="primary">Buscar por ID</v-btn>
    </v-form>
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
    };
  },
  methods: {
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
        this.$swal.fire('Erro', 'Erro ao pesquisar pessoa!', 'error');;
      }
    },
    async searchById() {
      try {
        const response = await axios.get(`/people/${this.searchId}`);
        this.results = [response.data];
        this.$emit('people-found', this.results);
      } catch (error) {
        console.error(error);
        this.$swal.fire('Erro', 'Erro ao pesquisar pessoa pelo ID!', 'error');
      }
    },
    selectPerson(person) {
      this.$emit('view-person', person);
    },
  },
};
</script>
