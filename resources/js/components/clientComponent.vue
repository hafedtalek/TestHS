<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <v-toolbar blue>
              <v-toolbar-title>Clients List</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
              ></v-text-field>
              <v-spacer></v-spacer>
              <v-btn icon @click="addClient">
                <v-icon>add</v-icon>
              </v-btn>
              <add-update-client
                :creationModalVisible.sync="creationModalVisible"
                :actionType="actionType"
                :updatedClient="updatedClient"
              ></add-update-client>
              <delete-client 
								:deleteModalVisible.sync="deleteModalVisible"
								:deletedClient="deletedClient"
							></delete-client>
              <manage-role 
								:roleModalVisible.sync="roleModalVisible"
                :updatedClient="updatedClient"
							></manage-role>
            </v-toolbar>
          </div>

          <div class="card-body">
            <v-data-table :headers="headers" :items="clients" :search="search" class="elevation-1">
              <template v-slot:items="props">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.firstname }} {{ props.item.lastname }}</td>
                <td>{{ props.item.email }}</td>
                <td>
                  <v-icon small class="mr-2" @click="manageClient(props.item)">list</v-icon>
                  <v-icon small class="mr-2" @click="editClient(props.item)">edit</v-icon>
                  <v-icon small @click="removeClient(props.item)">delete</v-icon>
                </td>
              </template>
            </v-data-table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AddUpdateClient from "./Modal/AddUpdateClient";
import deleteClient from "./Modal/DeleteClient";
import manageRole from "./Modal/ManageRole";

export default {
  components: {
    "add-update-client": AddUpdateClient,
    "delete-client": deleteClient,
		"manage-role": manageRole
  },

  name: "client-component",

  data() {
    return {
      //clients: [],
      search: "",
      pagination: {},
      updatedClient: null,
      deletedClient: null,
      loading: false,
      creationModalVisible: false,
      deleteModalVisible: false,
      roleModalVisible: false,
      actionType: "create",
      headers: [
        { text: "id", value: "id", sortable: true },
        { text: "Name", value: "name", sortable: true },
        { text: "Email", value: "email", sortable: true },
        { text: "Actions", sortable: false }
      ]
    };
  },

  mounted() {
    this.getClients();
  },
  computed: {
    clients: {
      get() {
        return this.$store.state.clients;
      }
    }
  },
  methods: {
    getClients() {
      axios.get(`/api/clients`).then(({ data }) => {
        this.$store.commit("getClient", data.data);
      });
    },

    editClient(client) {
      this.updatedClient = client;
      this.creationModalVisible = true;
      this.actionType = "update";
    },
    addClient() {
      this.updatedClient = null;
      this.creationModalVisible = true;
      this.actionType = "create";
    },
    removeClient(client) {
      this.deletedClient = client;
      this.deleteModalVisible = true;
    },
    manageClient(client) {
      this.updatedClient = client;
      this.roleModalVisible = true;
    }
  }
};
</script>
