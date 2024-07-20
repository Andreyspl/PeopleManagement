import { createApp } from 'vue';
import App from './App.vue';
import vuetify from './plugins/vuetify';
import '@mdi/font/css/materialdesignicons.css';
import Swal from 'sweetalert2';
import VueTheMask from 'vue-the-mask';

const app = createApp(App);

app.config.globalProperties.$swal = Swal;

app.use(VueTheMask);
app.use(vuetify).mount('#app');
