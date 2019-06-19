<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <v-toolbar blue>
              <v-toolbar-title>My User List</v-toolbar-title>
              <v-spacer></v-spacer>
              <v-text-field
                v-model="search"
                append-icon="search"
                label="Search"
                single-line
                hide-details
              ></v-text-field>
              <v-spacer></v-spacer>
              <v-btn icon @click="addUser">
                <v-icon>add</v-icon>
              </v-btn>
              <add-update-user
                :creationModalVisible.sync="creationModalVisible"
                :actionType="actionType"
                :updatedUser="updatedUser"
              ></add-update-user>
              <delete-user 
								:deleteModalVisible.sync="deleteModalVisible"
								:deletedUser="deletedUser"
							></delete-user>
              <manage-role 
								:roleModalVisible.sync="roleModalVisible"
                :updatedUser="updatedUser"
							></manage-role>
            </v-toolbar>
          </div>

          <div class="card-body">
            <v-data-table :headers="headers" :items="users" :search="search" class="elevation-1">
              <template v-slot:items="props">
                <td>{{ props.item.id }}</td>
                <td>{{ props.item.name }}</td>
                <td>{{ props.item.email }}</td>
                <td>
                  <v-icon small class="mr-2" @click="manageUser(props.item)">list</v-icon>
                  <v-icon small class="mr-2" @click="editUser(props.item)">edit</v-icon>
                  <v-icon small @click="removeUser(props.item)">delete</v-icon>
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
import AddUpdateUser from "./Modal/AddUpdateUser";
import deleteUser from "./Modal/DeleteUser";
import manageRole from "./Modal/ManageRole";
export default {
  components: {
    "add-update-user": AddUpdateUser,
    "delete-user": deleteUser,
    "manage-role": manageRole
  },

  name: "index-component",

  data() {
    return {
      //users: [],
      search: "",
      pagination: {},
      updatedUser: null,
      deletedUser: null,
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
    this.getUsers();
  },
  computed: {
    users: {
      get() {
        return this.$store.state.users;
      }
    }
  },
  methods: {
    getUsers() {
      axios.get(`/api/users`).then(({ data }) => {
        this.$store.commit("getUser", data.data);
      });
    },

    editUser(user) {
      this.updatedUser = user;
      this.creationModalVisible = true;
      this.actionType = "update";
    },
    addUser() {
      this.updatedUser = null;
      this.creationModalVisible = true;
      this.actionType = "create";
    },
    removeUser(user) {
      this.deletedUser = user;
      this.deleteModalVisible = true;
    },
    manageUser(user) {
      this.updatedUser = user;
      this.roleModalVisible = true;
    }
  }
};
</script>
