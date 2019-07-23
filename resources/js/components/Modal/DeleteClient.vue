<template>
  <v-dialog v-model="deleteModalVisible" persistent max-width="500px">
    <v-card v-if="deletedClient">
      <v-card-title>
        <span class="headline">Remove {{deletedClient.firstname}}</span>
      </v-card-title>

      <v-card-text>
        <v-container grid-list-md>
          <v-layout wrap>
            <v-flex xs12>Are you sure do you want to remove {{deletedClient.firstname}}</v-flex>
          </v-layout>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" flat @click="closeModal">Cancel</v-btn>
        <v-btn color="red darken-1" flat @click="remove">
          Remove          
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: "remove-client",

  props: {
    deleteModalVisible: Boolean,
    deletedClient: Object
  },
  methods: {
    closeModal() {
      this.$emit("update:deleteModalVisible", false);
    },
    remove() {
      self = this;
      axios
        .delete("/api/clients/" + this.deletedClient.id)
        .then(({ data }) => {
          this.$store.commit("deleteClient", this.deletedClient);
          this.closeModal();
          this.$notification.new("client Deleted");
        })
        .catch(function(errorResponse) {
          self.$notification.new("Client not deleted", {
            type: "error",
            position: "bottomCenter"
          });
        });
    }
  }
};
</script>

