<template>
  <v-dialog v-model="creationModalVisible" persistent max-width="500px">
    <v-card>
      <v-form ref="userForm" v-model="valide" lazy-validation>
        <v-card-title>
          <span class="headline">{{actionType}} User</span>
        </v-card-title>

        <v-card-text>
          <v-container grid-list-md>
            <v-layout wrap>
              <v-flex xs12>
                <v-text-field
                  v-model="userForm.name"
                  label="Name*"
                  :rules="[rules.required]"
                  required
                  :error-messages="errors.name"
                ></v-text-field>
              </v-flex>
              <v-flex xs12 v-if="actionType == 'create'">
                <v-text-field
                  v-model="userForm.email"
                  label="Email*"
                  :rules="[rules.required, rules.emailMatch]"
                  required
                  :error-messages="errors.email"
                ></v-text-field>
              </v-flex>
              <v-flex xs12 v-if="actionType == 'update'">
                <v-checkbox v-model="updatePasswordCheck"></v-checkbox>
              </v-flex>
              <v-flex xs12>
                <v-text-field
                  :append-icon="showPassword ? 'visibility' : 'visibility_off'"
                  :disabled="!updatePassword"
                  :rules="[rules.required]"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  hint="At least 6 characters"
                  v-model="userForm.password"
                  label="Password"
                  @click:append="showPassword = !showPassword"
                  :error-messages="errors.password"
                ></v-text-field>
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
  name: "add-update-user",

  props: {
    creationModalVisible: Boolean,
    actionType: String,
    updatedUser: Object
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
          this.filleForm(this.updatedUser)
        }
			},
		},
  
  data() {
    return {
      showPassword: false,
      updatePasswordCheck: false,
      valide: true,
      userForm: {
        id: "",
        name: "",
        email: "",
        password: "",
        updatePassword: "",
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
      if (this.$refs.userForm.validate()) {
        self = this;
        
        axios
          .post("/api/users", this.userForm)
          .then(({ data }) => {
            this.$store.commit("addUser", data.data)
            this.closeModal()
            this.$notification.new("user added succesfuly");
            //this.$emit("user_added", data);
          })
          .catch(function(errorResponse) {
            self.errors = errorResponse.response.data.errors
            self.$notification.new("User not Created", {  type: "error" , position: "bottomCenter"});
          });
      }
    },
    update() {
      if (this.$refs.userForm.validate()) {
        self = this;
        this.userForm.updatePassword = this.updatePasswordCheck
        axios
          .put("/api/users/"+this.updatedUser, this.userForm)
          .then(({ data }) => {
            this.$store.commit("updateUser", data.data)
            this.closeModal()
            this.$notification.new("user updated succesfuly");
            //this.$emit("user_updated", data);
          })
          .catch(function(errorResponse) {
            self.errors = errorResponse.response.data.errors
            self.$notification.new("User not Updated", {  type: "error" , position: "bottomCenter"});
          });
      }
    },
    resetForm() {
      this.userForm.name = "";
      this.userForm.email = "";
      this.userForm.password = "";
    },
    filleForm(user) {
      this.userForm.id = user.id;
      this.userForm.name = user.name;
      this.userForm.email = user.email;
      this.userForm.password = "******";
    }
  }
};
</script>

