<template>
    <v-container>
      <v-form @submit.prevent="searchPerson">
        <v-text-field v-model="searchTerm" label="Pesquisar por Nome ou CPF"></v-text-field>
        <v-btn type="submit" color="primary">Pesquisar</v-btn>
      </v-form>
      <v-list v-if="results.length">
        <v-list-item v-for="person in results" :key="person.id">
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
        } catch (error) {
          console.error(error);
          alert('Erro ao pesquisar pessoa.');
        }
      },
    },
  };
  </script>
  