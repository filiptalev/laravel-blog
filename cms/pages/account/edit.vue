<template>
  <div id="sc-page-wrapper">
    <div id="sc-page-content">
      <div class="uk-child-width-1" data-uk-grid>
        <div>
          <ScCard>
            <ScCardHeader separator>
              <div class="uk-flex uk-flex-middle">
                <i class="mdi mdi-plus uk-margin-medium-right sc-icon-24"></i>
                <ScCardTitle> Edit Your Profile </ScCardTitle>
              </div>
            </ScCardHeader>
            <ScCardBody>
              <form class="uk-form-stacked">
                <div class="uk-margin">
                  <ScInput
                    v-model="model.first_name"
                    :error-state="$v.model.first_name.$error"
                    :validator="$v.model.first_name"
                  >
                    <label class="required">First Name</label>
                  </ScInput>
                  <ul class="sc-vue-errors">
                    <li v-if="!$v.model.first_name.required">Field is required</li>
                  </ul>
                </div>
                <div class="uk-margin">
                  <ScInput
                    v-model="model.last_name"
                    :error-state="$v.model.last_name.$error"
                    :validator="$v.model.last_name"
                  >
                    <label class="required">Last Name</label>
                  </ScInput>
                  <ul class="sc-vue-errors">
                    <li v-if="!$v.model.last_name.required">Field is required</li>
                  </ul>
                </div>
                <div class="uk-margin">
                  <ScInput
                    v-model="model.email"
                    :error-state="$v.model.email.$error"
                    :validator="$v.model.email"
                    type="email"
                  >
                    <label class="required">Email</label>
                  </ScInput>
                  <ul class="sc-vue-errors">
                    <li v-if="!$v.model.email.required">Field is required</li>
                    <li v-if="!$v.model.email.email">Field must be valid email.</li>
                  </ul>
                </div>
                <div class="uk-margin">
                  <ScInput type="password" v-model="model.password">
                    <label class="required">Password</label>
                  </ScInput>
                </div>
                <div class="uk-margin">
                  <ScInput
                    type="password"
                    v-model="model.password_confirmation"
                    :error-state="$v.model.password_confirmation.$error"
                    :validator="$v.model.password_confirmation"
                  >
                    <label class="required">Confirm Password</label>
                  </ScInput>
                  <ul class="sc-vue-errors">
                    <li v-if="!$v.model.password_confirmation.required">
                      Field is required
                    </li>
                    <li v-if="!$v.model.password_confirmation.sameAs">
                      Passwords must match.
                    </li>
                  </ul>
                </div>
                <div class="uk-margin-medium-top">
                  <button
                    class="sc-button sc-button-primary"
                    :class="{ 'sc-button-progress-overlay': loading }"
                    :disabled="loading"
                    @click.prevent="handleSubmit"
                  >
                    <span>Update</span>
                    <transition name="scale-up">
                      <span v-show="loading" class="sc-button-progress-layer">
                        <ScProgressCircular></ScProgressCircular>
                      </span>
                    </transition>
                  </button>
                </div>
              </form>
            </ScCardBody>
          </ScCard>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ScInput from "~/components/Input";
import ScTextarea from "~/components/Textarea";
import PrettyCheck from "pretty-checkbox-vue/check";
import { ScProgressCircular } from "~/components/progress";

import { validationMixin } from "vuelidate";
import { required, email, sameAs } from "vuelidate/lib/validators";

if (process.client) {
  require("~/plugins/flatpickr");
}

export default {
  middleware: "middleware-admin-role",
  name: "FormsExamplesContact",
  components: {
    ScInput,
    ScTextarea,
    PrettyCheck,
    ScProgressCircular,
  },
  mixins: [validationMixin],
  data: () => ({
    model: {
      id: "",
      first_name: "",
      last_name: "",
      email: "",
      password: "",
      password_confirmation: "",
    },
    loading: false,
  }),
  validations: {
    model: {
      first_name: {
        required,
      },
      last_name: {
        required,
      },
      email: {
        required,
        email,
      },
      password_confirmation: {
        sameAs: sameAs("password"),
      },
    },
  },
  async asyncData({ $axios, app, params }) {
    try {
      const item = await $axios.$get(`me`);

      return {
        model: item.data,
      };
    } catch (e) {
      this.$toast.error(Object.values(e.response.data.messages).join(" "));
    }
  },
  methods: {
    async handleSubmit() {
      this.$v.$touch();
      if (this.$v.$invalid) {
        return;
      }

      this.loading = true;

      const formData = new FormData();
      for (var key in this.model) {
        if (this.model[key]) formData.append(key, this.model[key]);
      }

      // formData.append("_method", "patch");

      try {
        await this.$axios.$post("users/" + this.model.id, formData);
        //redirect
        this.$router.replace("/users");
        this.$toast.success("User updated successfully.");
      } catch (e) {
        this.loading = false;
        this.$toast.error(Object.values(e.response.data.messages).join(" "));
      }
    },
  },
};
</script>

<style lang="scss">
@import "~scss/vue/_pretty_checkboxes";
</style>
