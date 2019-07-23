<template>
  <v-dialog v-model="creationModalVisible" persistent max-width="500px">
    <v-card>
      <v-form ref="clientForm" v-model="valide" lazy-validation>
        <v-card-title>
          <span class="headline">{{actionType}} Client</span>
        </v-card-title>

        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs6>
                <v-text-field
                  v-model="clientForm.firstname"
                  label="Firstname*"
                  :rules="[rules.required]"
                  required
                  :error-messages="errors.firstname"
                ></v-text-field>
              </v-flex>
              <v-flex xs6>
                <v-text-field
                  v-model="clientForm.lastname"
                  label="Lastname*"
                  :rules="[rules.required]"
                  required
                  :error-messages="errors.lastname"
                ></v-text-field>
              </v-flex>
              <v-flex xs6>
                <v-text-field
                  v-model="clientForm.email"
                  label="Email*"
                  :rules="[rules.required, rules.emailMatch]"
                  required
                  :error-messages="errors.email"
                ></v-text-field>
              </v-flex>
              <v-flex xs6>
                <v-text-field
                  v-model="clientForm.phone"
                  label="Phone*"
                  :rules="[rules.required]"
                  required
                  :error-messages="errors.phone"
                ></v-text-field>
              </v-flex>
              <v-flex xs12>
                <v-textarea
                  v-model="clientForm.comment"
                  label="Comment*"
                  auto-grow
                  rows="2"
                ></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="blue darken-1" flat @click="closeModal">Cancel</v-btn>
          <v-btn color="blue darken-1" v-if="actionType == 'create'" flat @click="save" submit>Save</v-btn>
          <v-btn color="blue darken-1" v-if="actionType == 'update'" flat @click="update" submit>update</v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>
<script>
export default {
  name: "add-update-client",

  props: {
    creationModalVisible: Boolean,
    actionType: String,
    updatedClient: Object
  },
  computed: {
    updatePassword: function() {
      return this.actionType == "create" ? true : this.updatePasswordCheck;
    }
  },
  watch: {
      "creationModalVisible": function (val) {
        if(this.actionType == "create"){
          this.resetForm()
        }else{
          this.filleForm(this.updatedClient)
        }
			},
		},
  
  data() {
    return {
      showPassword: false,
      updatePasswordCheck: false,
      valide: true,
      clientForm: {
        id: "",
        firstname: "",
        lastname: "",
        email: "",
        phone: "",
        comment: "",
      },
      errors: {
        name: "",
        email: "",
        password: ""
      },
      rules: {
        required: value => !!value || "This field is Required.",
        emailMatch: v => /.+@.+/.test(v) || "E-mail must be valid"
      }
    };
  },

  methods: {
    closeModal() {
      this.$emit("update:creationModalVisible", false);
    },

    save() {
      if (this.$refs.clientForm.validate()) {
        self = this;
        
        axios
          .post("/api/clients", this.clientForm)
          .then(({ data }) => {
            this.$store.commit("addClient", data.data)
            this.closeModal()
            this.$notification.new("client added succesfuly");
            //this.$emit("client_added", data);
          })
          .catch(function(errorResponse) {
            self.errors = errorResponse.response.data.errors
            self.$notification.new("Client not Created", {  type: "error" , position: "bottomCenter"});
          });
      }
    },
    update() {
      if (this.$refs.clientForm.validate()) {
        self = this;
        this.clientForm.updatePassword = this.updatePasswordCheck
        axios
          .put("/api/clients/"+this.updatedClient, this.clientForm)
          .then(({ data }) => {
            this.$store.commit("updateClient", data.data)
            this.closeModal()
            this.$notification.new("client updated succesfuly");
            //this.$emit("client_updated", data);
          })
          .catch(function(errorResponse) {
            self.errors = errorResponse.response.data.errors
            self.$notification.new("Client not Updated", {  type: "error" , position: "bottomCenter"});
          });
      }
    },
    resetForm() {
      this.clientForm.firstname = "";
      this.clientForm.lastname = "";
      this.clientForm.email = "";
      this.clientForm.phone = "";
      this.clientForm.comment = "";
    },
    filleForm(client) {
      this.clientForm.id = client.id;
      this.clientForm.firstname = client.firstname;
      this.clientForm.lastname = client.lastname;
      this.clientForm.email = client.email;
      this.clientForm.phone = client.phone;
      this.clientForm.comment = client.comment;
    }
  }
};
</script>

