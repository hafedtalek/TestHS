<template>
  <v-dialog v-model="deleteModalVisible" persistent max-width="500px">
    <v-card v-if="deletedUser">
      <v-card-title>
        <span class="headline">Remove {{deletedUser.name}}</span>
      </v-card-title>

      <v-card-text>
        <v-container grid-list-md>
          <v-layout wrap>
            <v-flex xs12>Are you sure do you want to remove {{deletedUser.name}}</v-flex>
          </v-layout>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" flat @click="closeModal">Cancel</v-btn>
        <v-btn color="red darken-1" flat @click="remove">
          Remove
          <vue-simple-spinner v-if="loading"></vue-simple-spinner>
          
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: "remove-user",

  props: {
    deleteModalVisible: Boolean,
    deletedUser: Object
  },
  data() {
    return {
      loading: false
    };
  },
  methods: {
    closeModal() {
      this.$emit("update:deleteModalVisible", false);
    },
    remove() {
      self = this;
      this.loading = true;
      axios
        .delete("/api/users/" + this.deletedUser.id)
        .then(({ data }) => {
          this.loading = false;
          this.$store.commit("deleteUser", this.deletedUser);
          this.closeModal();
          this.$notification.new("user Deleted");
        })
        .catch(function(errorResponse) {
          this.loading = false;
          self.$notification.new("User not deleted", {
            type: "error",
            position: "bottomCenter"
          });
        });
    }
  }
};
</script>

