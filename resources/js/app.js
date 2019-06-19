/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import Vue from 'vue'
import Vuex from 'vuex'
import Vuetify from 'vuetify'
import myComponenet from './components/IndexComponent'
import VueNotification from "@kugatsu/vuenotification"


Vue.use(VueNotification, {timer: 5});
Vue.use(Vuetify)
Vue.use(Vuex)
Vue.use(myComponenet)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
Vue.component(myComponenet.name, myComponenet)

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
var store = new Vuex.Store({
  state: {
    users: []
  },
  mutations: {
    getUser (state, users) {
      state.users = users
    },
    addUser (state, user) {
      state.users.unshift(user)
    },
    deleteUser (state, user) {
      state.users = state.users.filter(function(obj){
        return obj.id != user.id 
      });
    },
    updateUser (state, user) {
      let index = state.users.findIndex(function(obj){
        return obj.id == user.id 
      });
      Vue.set(state.users, index, user)
    }
  }
})
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store: store,
});
