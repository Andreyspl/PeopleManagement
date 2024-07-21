<template>
    <v-app>
      <v-main>
        <v-container>
          <h1>Sistema de gerenciamento de Pessoas</h1>
          <CreatePerson @person-added="handlePersonAdded"></CreatePerson>
          <SearchPerson @people-found="handlePeopleFound" @view-person="viewPerson"></SearchPerson>
  
          <div v-if="searchMessage">
            <h2>{{ searchMessage }}</h2>
          </div>
  
          <v-dialog v-model="isEditPersonModalVisible" max-width="600px">
            <v-card>
              <v-card-title>
                <span class="headline">Editar Pessoa</span>
              </v-card-title>
              <v-card-text>
                <v-form @submit.prevent="submitEditPersonForm">
                  <v-text-field v-if="selectedPerson" v-model="selectedPerson.social_name" label="Nome Social"></v-text-field>
                  <v-text-field v-if="selectedPerson" v-model="selectedPerson.father_name" label="Nome do Pai"></v-text-field>
                  <v-text-field v-if="selectedPerson" v-model="selectedPerson.mother_name" label="Nome da Mãe"></v-text-field>
                  <v-text-field v-if="selectedPerson" v-model="selectedPerson.phone" label="Telefone"></v-text-field>
                  <v-text-field v-if="selectedPerson" v-model="selectedPerson.email" label="Email"></v-text-field>
                  <v-btn type="submit" color="primary">Salvar</v-btn>
                  <v-btn @click="isEditPersonModalVisible = false" color="secondary">Cancelar</v-btn>
                </v-form>
              </v-card-text>
            </v-card>
          </v-dialog>
  
          <DetailPersonModal
            v-if="isDetailPersonModalVisible"
            :person="selectedPerson"
            @close="isDetailPersonModalVisible = false"
            @edit="openEditPersonModal"
            @add-address="openAddressModal"
            @view-history="openAddressHistory"
          ></DetailPersonModal>
  
          <AddressModal
            v-if="isAddressModalVisible"
            :personId="selectedPerson ? selectedPerson.id : null"
            :initialAddress="addressToEdit || {}"
            @close="isAddressModalVisible = false"
            @address-added="handleAddressAdded"
            @address-updated="handleAddressAdded"
          ></AddressModal>
  
          <AddressHistoryModal
            v-if="isAddressHistoryVisible"
            :personId="selectedPerson ? selectedPerson.id : null"
            @close="isAddressHistoryVisible = false"
            @edit-address="openEditAddressModal"
          ></AddressHistoryModal>
        </v-container>
      </v-main>
    </v-app>
  </template>
  
  <script>
  import CreatePerson from './Components/CreatePerson.vue';
  import SearchPerson from './Components/SearchPerson.vue';
  import AddressModal from './Components/AddressModal.vue';
  import DetailPersonModal from './Components/DetailPersonModal.vue';
  import AddressHistoryModal from './Components/AddressHistoryModal.vue';
  import axios from 'axios';
  
  export default {
    components: {
      CreatePerson,
      SearchPerson,
      AddressModal,
      DetailPersonModal,
      AddressHistoryModal,
    },
    data() {
      return {
        people: [],
        selectedPerson: null,
        isEditPersonModalVisible: false,
        isDetailPersonModalVisible: false,
        isAddressModalVisible: false,
        isAddressHistoryVisible: false,
        searchMessage: '',
        addressToEdit: null,
      };
    },
    methods: {
      handlePersonAdded(person) {
        this.people.push(person);
      },
      handlePeopleFound(people) {
        this.people = people;
      },
      updateSearchMessage(message) {
        this.searchMessage = message;
      },
      viewPerson(person) {
        this.selectedPerson = person;
        if (!this.selectedPerson.addresses) {
          this.selectedPerson.addresses = [];
        }
        this.isDetailPersonModalVisible = true;
      },
      handleAddressAdded(address) {
        if (this.selectedPerson && this.selectedPerson.addresses) {
          this.selectedPerson.addresses.push(address);
        }
      },
      openEditPersonModal() {
        this.isDetailPersonModalVisible = false;
        this.isEditPersonModalVisible = true;
      },
      openAddressModal() {
        this.isDetailPersonModalVisible = false;
        this.isAddressModalVisible = true;
      },
      openEditAddressModal(address) {
        this.addressToEdit = address;
        this.isAddressModalVisible = true;
      },
      openAddressHistory() {
        this.isDetailPersonModalVisible = false;
        this.isAddressHistoryVisible = true;
      },
      async submitEditPersonForm() {
        try {
          const response = await axios.put(`/people/${this.selectedPerson.id}`, this.selectedPerson);
          this.isEditPersonModalVisible = false;
          this.$swal.fire('Sucesso', 'Dados da pessoa atualizados com sucesso!', 'success');
        } catch (error) {
          console.error(error);
          this.$swal.fire('Erro', 'Erro ao atualizar os dados da pessoa.', 'error');
        }
      }
    }
  };
  </script>
  