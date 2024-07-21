<template>
    <v-dialog v-model="dialog" max-width="800px">
      <v-card>
        <v-card-title>
          <span class="headline">Histórico de Endereços</span>
        </v-card-title>
        <v-card-text>
          <v-list>
            <v-list-item v-for="address in addressHistory" :key="address.id">
              <v-list-item-title>
                {{ getAddressType(address.type) }}
                <span v-if="address.deleted_at" class="deleted-warning">(Deletado)</span>
              </v-list-item-title>
              <v-list-item-subtitle>
                {{ address.logradouro }}, {{ address.number }} - {{ address.city }} / {{ address.state }}
              </v-list-item-subtitle>
              <v-list-item-action class="action-buttons">
                <v-btn v-if="!address.deleted_at" icon @click="editAddress(address)">
                  <v-icon>mdi-pencil</v-icon>
                </v-btn>
                <v-btn v-if="!address.deleted_at" icon @click="confirmDeleteAddress(address)">
                  <v-icon>mdi-delete</v-icon>
                </v-btn>
              </v-list-item-action>
            </v-list-item>
          </v-list>
        </v-card-text>
        <v-card-actions>
          <v-btn @click="closeModal" color="secondary">Fechar</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    props: {
      personId: {
        type: Number,
        required: true,
      },
    },
    data() {
      return {
        dialog: true,
        addressHistory: [],
      };
    },
    created() {
      this.fetchAddressHistory();
    },
    methods: {
      async fetchAddressHistory() {
        try {
          const response = await axios.get(`/people/${this.personId}/addresses`);
          this.addressHistory = response.data;
        } catch (error) {
          console.error('Erro ao buscar histórico de endereços:', error);
        }
      },
      closeModal() {
        this.dialog = false;
        this.$emit('close');
      },
      editAddress(address) {
        this.$emit('edit-address', address);
        this.closeModal();
      },
      confirmDeleteAddress(address) {
        this.closeModal();
        this.$swal.fire({
          title: 'Você tem certeza?',
          text: 'Esta ação não pode ser desfeita!',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sim, deletar!',
          cancelButtonText: 'Cancelar',
        }).then((result) => {
          if (result.isConfirmed) {
            this.deleteAddress(address);
          }
        });
      },
      async deleteAddress(address) {
        try {
          await axios.delete(`/addresses/${address.id}`);
          address.deleted_at = new Date().toISOString();
          this.$swal.fire('Deletado!', 'O endereço foi deletado.', 'success');
          this.fetchAddressHistory();
        } catch (error) {
            this.closeModal();
          console.error('Erro ao deletar endereço:', error);
          this.$swal.fire('Erro', 'Erro ao deletar o endereço.', 'error');
        }
      },
      getAddressType(type) {
      const types = {
        residential: 'Residencial',
        commercial: 'Comercial'
      };
      return types[type] || type;
    },
    },
  };
  </script>
  
  <style>
  .deleted-warning {
    color: red;
    font-weight: bold;
  }
  .action-buttons {
    display: flex;
    gap: 8px;
  }
  </style>
  