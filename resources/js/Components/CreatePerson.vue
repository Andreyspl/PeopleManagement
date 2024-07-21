<template>
  <v-container>
    <v-form ref="createPersonForm" @submit.prevent="submitForm">
      <v-text-field
        v-model="person.name"
        label="Nome"
        :rules="[v => !!v || 'Nome é obrigatório']"
        required
      ></v-text-field>
      <v-text-field
        v-model="person.social_name"
        label="Nome Social"
      ></v-text-field>
      <v-text-field
        v-model="person.cpf"
        label="CPF"
        v-mask="'###.###.###-##'"
        :rules="[v => !!v || 'CPF é obrigatório', validateCPF]"
        required
      ></v-text-field>
      <v-text-field
        v-model="person.father_name"
        label="Nome do Pai"
      ></v-text-field>
      <v-text-field
        v-model="person.mother_name"
        label="Nome da Mãe"
      ></v-text-field>
      <v-text-field
        v-model="person.phone"
        label="Telefone"
        v-mask="'(##) #####-####'"
        :rules="[v => !!v || 'Telefone é obrigatório']"
        required
      ></v-text-field>
      <v-text-field
        v-model="person.email"
        label="Email"
        :rules="[v => !v || /.+@.+\..+/.test(v) || 'E-mail deve ser válido']"
      ></v-text-field>
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
    validateCPF(value) {
      const cpfPattern = /^\d{3}\.\d{3}\.\d{3}-\d{2}$/;
      if (!cpfPattern.test(value)) {
        return 'CPF deve ser válido';
      }

      value = value.replace(/[^\d]+/g, '');
      if (value.length !== 11 || /^(\d)\1+$/.test(value)) {
        return 'CPF deve ser válido';
      }

      let sum = 0;
      let remainder;
      for (let i = 1; i <= 9; i++) {
        sum += parseInt(value.substring(i - 1, i)) * (11 - i);
      }
      remainder = (sum * 10) % 11;

      if (remainder === 10 || remainder === 11) {
        remainder = 0;
      }
      if (remainder !== parseInt(value.substring(9, 10))) {
        return 'CPF deve ser válido';
      }

      sum = 0;
      for (let i = 1; i <= 10; i++) {
        sum += parseInt(value.substring(i - 1, i)) * (12 - i);
      }
      remainder = (sum * 10) % 11;

      if (remainder === 10 || remainder === 11) {
        remainder = 0;
      }
      if (remainder !== parseInt(value.substring(10, 11))) {
        return 'CPF deve ser válido';
      }

      return true;
    },
    async submitForm() {
      if (!this.person.name || !this.person.cpf || !this.person.phone) {
        this.$swal.fire('Erro', 'Nome, CPF e Telefone são obrigatórios!', 'error');
        return;
      }
      
      try {
        const response = await axios.post('/people', this.person);
        this.$swal.fire('Cadastrada!', 'A pessoa foi cadastrada com sucesso!', 'success');
        this.resetForm();
      } catch (error) {
        console.error(error);
        this.$swal.fire('Erro', 'Erro ao cadastrar pessoa!', 'error');
      }
    },
    resetForm() {
      this.person = {
        name: '',
        social_name: '',
        cpf: '',
        father_name: '',
        mother_name: '',
        phone: '',
        email: '',
      };
      this.$refs.createPersonForm.reset();
    },
  },
};
</script>

<style scoped>
.v-text-field {
  margin-bottom: 20px;
}
</style>
