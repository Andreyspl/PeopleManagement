<template>
    <v-dialog v-model="dialog" max-width="800px">
      <v-card>
        <v-card-title>
          <span class="headline">Detalhes da Pessoa</span>
        </v-card-title>
        <v-card-text>
          <p><strong>Nome:</strong> {{ person.name }}</p>
          <p><strong>CPF:</strong> {{ person.cpf }}</p>
          <p><strong>Nome Social:</strong> {{ person.social_name }}</p>
          <p><strong>Nome do Pai:</strong> {{ person.father_name }}</p>
          <p><strong>Nome da Mãe:</strong> {{ person.mother_name }}</p>
          <p><strong>Telefone:</strong> {{ person.phone }}</p>
          <p><strong>Email:</strong> {{ person.email }}</p>
          <v-divider></v-divider>
          <v-list v-if="person.addresses && person.addresses.length">
            <template v-for="address in person.addresses" :key="address.id">
              <v-list-item>
                  <v-list-item-title>{{ address.logradouro }}, {{ address.number }}</v-list-item-title>
                  <v-list-item-subtitle>{{ address.city }} - {{ address.state }}</v-list-item-subtitle>
              </v-list-item>
              <v-divider></v-divider>
            </template>
          </v-list>
        </v-card-text>
        <v-card-actions>
          <v-btn icon @click="editPerson">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
          <v-btn icon @click="addAddress">
            <v-icon>mdi-home</v-icon>
          </v-btn>
          <v-btn icon @click="viewHistory">
            <v-icon>mdi-history</v-icon>
          </v-btn>
          <v-btn @click="closeModal">Fechar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script>
  export default {
    props: ['person'],
    data() {
      return {
        dialog: true,
      };
    },
    methods: {
      closeModal() {
        this.dialog = false;
        this.$emit('close');
      },
      editPerson() {
        this.$emit('close');
        this.$emit('edit');
      },
      addAddress() {
        this.$emit('close');
        this.$emit('add-address');
      },
      viewHistory() {
        this.$emit('close');
        this.$emit('view-history');
      },
    },
  };
  </script>
  