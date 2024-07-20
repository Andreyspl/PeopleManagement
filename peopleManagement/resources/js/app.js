import { createApp } from 'vue';
import App from './App.vue';
import vuetify from './plugins/vuetify';

import CreatePerson from './Components/CreatePerson.vue';
import SearchPerson from './Components/SearchPerson.vue';

const app = createApp(App);

app.component('create-person', CreatePerson);
app.component('search-person', SearchPerson);

app.use(vuetify).mount('#app');
