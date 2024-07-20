<template>
    <v-dialog v-model="dialog" max-width="600px">
      <v-card>
        <v-card-title>
          <span class="headline">Adicionar/Editar Endereço</span>
        </v-card-title>
        <v-card-text>
          <v-form @submit.prevent="submitForm">
            <v-select v-model="addressType" :items="['Residencial', 'Comercial']" label="Tipo de Endereço" required></v-select>
            <v-text-field v-model="addressData.cep" label="CEP" @blur="fetchAddress" required></v-text-field>
            <v-text-field v-model="addressData.logradouro" :disabled="addressFromAPI" label="Logradouro" required></v-text-field>
            <v-text-field v-model="addressData.number" label="Número" required></v-text-field>
            <v-text-field v-model="addressData.complement" label="Complemento"></v-text-field>
            <v-text-field v-model="addressData.neighborhood" :disabled="addressFromAPI" label="Bairro" required></v-text-field>
            <v-text-field v-model="addressData.state" :disabled="addressFromAPI" label="Estado" required></v-text-field>
            <v-text-field v-model="addressData.city" :disabled="addressFromAPI" label="Cidade" required></v-text-field>
            <v-btn type="submit" color="primary">Salvar</v-btn>
            <v-btn @click="closeModal" color="secondary">Cancelar</v-btn>
          </v-form>
        </v-card-text>
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
      initialAddress: {
        type: Object,
        default: () => ({
          person_id: null,
          type: '',
          cep: '',
          logradouro: '',
          number: '',
          complement: '',
          neighborhood: '',
          state: '',
          city: '',
        }),
      },
    },
    data() {
      return {
        dialog: true,
        addressData: { ...this.initialAddress, person_id: this.personId },
        addressType: '',
        addressFromAPI: false,
      };
    },
    watch: {
      initialAddress: {
        handler(newVal) {
          this.addressData = { ...newVal, person_id: this.personId };
          this.addressType = newVal.type === 'residential' ? 'Residencial' : newVal.type === 'commercial' ? 'Comercial' : '';
        },
        immediate: true,
      },
    },
    methods: {
      async fetchAddress() {
        if (this.addressData.cep) {
          try {
            const response = await axios.get(`https://viacep.com.br/ws/${this.addressData.cep}/json/`);
            const data = response.data;
            if (!data.erro) {
              this.addressData.logradouro = data.logradouro;
              this.addressData.neighborhood = data.bairro;
              this.addressData.city = data.localidade;
              this.addressData.state = data.uf;
              this.addressFromAPI = true;
            } else {
              this.addressFromAPI = false;
            }
          } catch (error) {
            console.error('Erro ao buscar endereço:', error);
            this.addressFromAPI = false;
          }
        }
      },
      async submitForm() {
        try {
          this.addressData.type = this.addressType === 'Residencial' ? 'residential' : 'commercial';
          let response;
          if (this.addressData.id) {
            response = await axios.put(`/addresses/${this.addressData.id}`, this.addressData);
            this.$emit('address-updated', response.data);
          } else {
            response = await axios.post(`/addresses`, this.addressData);
            this.$emit('address-added', response.data);
            this.$swal.fire('Sucesso', 'Endereço adicionado com sucesso!', 'success')
          }
          this.closeModal();
        } catch (error) {
          console.error(error);
          this.$swal.fire('Erro', 'Erro ao salvar o endereço', 'error');
        }
      },
      closeModal() {
        this.dialog = false;
        this.$emit('close');
      },
    },
  };
  </script>
  