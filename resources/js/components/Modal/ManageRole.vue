<template>
  <v-dialog v-model="roleModalVisible" persistent max-width="500px">
    <v-card v-if="updatedUser">
      <v-card-title>
        <span class="headline">Adminitrative Group{{updatedUser.name}}</span>
      </v-card-title>

      <v-card-text>
        <v-container grid-list-md>
          <v-layout wrap>
            <v-flex xs12>
               <v-checkbox v-model="updatedUser.role.has_administration_rights" label="Administration"></v-checkbox>
               <v-checkbox v-model="updatedUser.role.has_onboarding_rights" label="on Boarding"></v-checkbox>
               <v-checkbox v-model="updatedUser.role.has_view_user_rights" label="Users"></v-checkbox>
            </v-flex>
          </v-layout>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" flat @click="closeModal">Cancel</v-btn>
        <v-btn color="orange darken-1" flat @click="update">Update</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: "manage-role",

  props: {
    roleModalVisible: Boolean,
    updatedUser: Object
  },

  methods: {
    closeModal() {
      this.$emit("update:roleModalVisible", false);
    },

    update() {
      self = this;
      axios
        .post(`/api/users/role/${this.updatedUser.id}`, this.updatedUser.role)
        .then(({ data }) => {
          this.$store.commit("updateUser", this.updatedUser);
          this.closeModal();
          this.$notification.new("Roles updated");
        })
        .catch(function(errorResponse) {
          self.$notification.new("Roles not Updated", {
            type: "error",
            position: "bottomCenter"
          });
        });
    }
  }
};
</script>

