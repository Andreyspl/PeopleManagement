<template>
    <v-container>
      <v-form @submit.prevent="submitForm">
        <v-text-field v-model="person.name" label="Nome" required></v-text-field>
        <v-text-field v-model="person.social_name" label="Nome Social"></v-text-field>
        <v-text-field v-model="person.cpf" label="CPF" required></v-text-field>
        <v-text-field v-model="person.father_name" label="Nome do Pai"></v-text-field>
        <v-text-field v-model="person.mother_name" label="Nome da Mãe"></v-text-field>
        <v-text-field v-model="person.phone" label="Telefone"></v-text-field>
        <v-text-field v-model="person.email" label="Email"></v-text-field>
        <v-btn type="submit" color="primary">Cadastrar</v-btn>
      </v-form>
    </v-container>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        person: {
          name: '',
          social_name: '',
          cpf: '',
          father_name: '',
          mother_name: '',
          phone: '',
          email: '',
        },
      };
    },
    methods: {
      async submitForm() {
        try {
          const response = await axios.post('/people', this.person);
          alert('Pessoa cadastrada com sucesso!');
        } catch (error) {
          console.error(error);
          alert('Erro ao cadastrar pessoa.');
        }
      },
    },
  };
  </script>
  