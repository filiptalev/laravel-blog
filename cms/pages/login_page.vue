<template>
  <div class="uk-flex uk-flex-center uk-flex-middle sc-login-page-wrapper">
    <div
      class="uk-width-2-3@s uk-width-1-2@m uk-width-1-3@l uk-width-1-4@xl uk-margin-auto-vertical"
    >
      <ScCard>
        <ScCardBody>
          <div class="sc-login-page-logo">
            <img v-rjs="require('~/assets/img/logo.png')" :src="appLogo" alt="" />
          </div>
          <div
            id="sc-login-form"
            class="sc-toggle-login-register sc-toggle-login-password"
          >
            <div class="sc-login-page-inner">
              <div class="uk-margin-medium">
                <ScInput
                  v-model="loginData.email"
                  name="email"
                  :error-state="$v.loginData.email.$error"
                  :validator="$v.loginData.email"
                >
                  <label> Email/Login </label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.loginData.email.required">Field is required.</li>
                  <li v-if="!$v.loginData.email.email">Field must be email.</li>
                </ul>
              </div>
              <div class="uk-margin-medium">
                <ScInput
                  v-model="loginData.password"
                  type="password"
                  name="password"
                  :error-state="$v.loginData.password.$error"
                  :validator="$v.loginData.password"
                >
                  <label> Password </label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.loginData.password.required">Field is required.</li>
                </ul>
                <div class="uk-margin-small-top uk-text-small uk-text-right@s">
                  <a
                    href="javascript:void(0)"
                    class="sc-link"
                    data-uk-toggle="target: .sc-toggle-login-password; animation: uk-animation-scale-up"
                    @click="forgotPassword = 1"
                  >
                    Forgot Password?
                  </a>
                </div>
              </div>
              <ul v-if="authError" class="sc-vue-errors uk-display-block">
                <li>
                  {{ authError }}
                </li>
              </ul>
              <div class="uk-margin-large-top">
                <button
                  class="sc-button sc-button-large sc-button-block sc-button-danger"
                  style="background: #c1b49a"
                  :class="{ 'sc-button-progress-overlay': loading }"
                  :disabled="loading"
                  @click.prevent="login()"
                >
                  <span>Sign In</span>

                  <transition name="scale-up">
                    <span v-show="loading" class="sc-button-progress-layer">
                      <ScProgressCircular style="margin-top: 12px"></ScProgressCircular>
                    </span>
                  </transition>
                </button>
              </div>
              <div class="uk-margin-large-top uk-text-center">
                <span class="sc-color-secondary"> Don't have an account? </span>
                <div>
                  <a
                    href="javascript:void(0)"
                    class="sc-text-semibold"
                    data-uk-toggle="target: .sc-toggle-login-register; animation: uk-animation-scale-up"
                  >
                    Sign Up
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div id="sc-register-form" class="sc-toggle-login-register" hidden>
            <div class="sc-login-page-inner">
              <div class="uk-margin-medium">
                <ScInput
                  v-model="registerData.first_name"
                  :error-state="$v.registerData.first_name.$error"
                  :validator="$v.registerData"
                >
                  <label> First Name </label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.registerData.first_name.required">Field is required.</li>
                </ul>
              </div>
              <div class="uk-margin-medium">
                <ScInput
                  v-model="registerData.last_name"
                  :error-state="$v.registerData.last_name.$error"
                  :validator="$v.registerData"
                >
                  <label> Last Name </label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.registerData.last_name.required">Field is required.</li>
                </ul>
              </div>
              <div class="uk-margin-medium">
                <ScInput
                  v-model="registerData.email"
                  :error-state="$v.registerData.email.$error"
                  :validator="$v.registerData"
                >
                  <label> Email </label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.registerData.email.required">Field is required.</li>
                  <li v-if="!$v.registerData.email.email">Field must be email.</li>
                </ul>
              </div>
              <div class="uk-margin-medium">
                <ScInput
                  type="password"
                  :error-state="$v.registerData.password.$error"
                  :validator="$v.registerData"
                  v-model="registerData.password"
                >
                  <label> Password </label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.registerData.password.required">Field is required.</li>
                </ul>
              </div>
              <div class="uk-margin-medium">
                <ScInput
                  :error-state="$v.registerData.password_confirmation.$error"
                  :validator="$v.registerData"
                  type="password"
                  v-model="registerData.password_confirmation"
                >
                  <label> Password Confirm</label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.registerData.password_confirmation.required">
                    Field is required.
                  </li>
                  <li v-if="!$v.registerData.password_confirmation.sameAsPassword">
                    Passwords dont match.
                  </li>
                </ul>
              </div>
              <div class="uk-margin-large-top">
                <button
                  class="sc-button sc-button-large sc-button-block sc-button-secondary"
                  @click.stop.prevent="register"
                >
                  Sign Up
                </button>
                <div class="uk-margin-large-top uk-flex uk-flex-middle uk-flex-center">
                  <a
                    ref="backToLoginFormFromRegister"
                    href="javascript:void(0)"
                    class="sc-text-semibold"
                    data-uk-toggle="target: .sc-toggle-login-register; animation: uk-animation-scale-up"
                  >
                    Back to login form
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div id="sc-password-form" class="sc-toggle-login-password" hidden>
            <div class="sc-login-page-inner">
              <div class="uk-margin-medium">
                Please enter your email address. You will receive a link to reset your
                password.
              </div>
              <div class="uk-margin-medium">
                <ScInput
                  v-model="recoverPassEmail"
                  :error-state="$v.recoverPassEmail.$error"
                  :validator="$v.recoverPassEmail"
                >
                  <label> Email </label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.recoverPassEmail.required">Field is required.</li>
                  <li v-if="!$v.recoverPassEmail.email">Field must be email.</li>
                </ul>
              </div>
              <div class="uk-margin-large-top">
                <button
                  class="sc-button sc-button-large sc-button-block sc-button-primary"
                  @click="resetPassword"
                >
                  Reset Password
                </button>
                <div class="uk-margin-large-top uk-flex uk-flex-middle uk-flex-center">
                  <a
                    href="javascript:void(0)"
                    class="sc-text-semibold"
                    data-uk-toggle="target: .sc-toggle-login-password; animation: uk-animation-scale-up"
                    @click="forgotPassword = 0"
                    ref="backToLoginForm"
                  >
                    Back to login form
                  </a>
                </div>
              </div>
            </div>
          </div>
        </ScCardBody>
      </ScCard>
    </div>
  </div>
</template>

<script>
import ScInput from "~/components/Input";
import { validationMixin } from "vuelidate";
import { required, email, sameAs } from "vuelidate/lib/validators";
import { ScProgressCircular } from "~/components/progress";

export default {
  name: "LoginPage",
  components: {
    ScInput,
    ScProgressCircular,
  },
  layout: "login_page",
  middleware({ store, redirect }) {
    if (store.state.authenticated) {
      return redirect("/account");
    }
  },
  auth: "guest",
  mixins: [validationMixin],
  data: () => ({
    loginData: {
      email: "",
      password: "",
    },
    registerData: {
      first_name: "",
      last_name: "",
      email: "",
      password: "",
      password_confirmation: "",
    },
    recoverPassEmail: "",
    authError: null,
    forgotPassword: 0,
    loading: false,
  }),
  validations: {
    loginData: {
      email: {
        required,
        email,
      },
      password: {
        required,
      },
    },
    recoverPassEmail: {
      required,
      email,
    },
    registerData: {
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
      password: {
        required,
      },
      password_confirmation: {
        required,
        sameAsPassword: sameAs("password"),
      },
    },
  },
  head() {
    return {
      title: "Riskclick Admin Login Page",
    };
  },
  computed: {
    appLogo() {
      return require("~/assets/img/logo.png");
    },
  },
  mounted() {
    this.$axios.$get("/sanctum/csrf-cookie");

    if (this.$auth.$state.loggedIn) {
      this.$router.push({
        path: "/",
      });
    }

    window.addEventListener("keyup", this.keyUp);
  },
  beforeDestroy() {
    window.removeEventListener("keyup", this.keyUp);
  },
  methods: {
    async login() {
      this.$v.$touch();

      if (this.$v.loginData.$invalid) {
        return;
      }

      this.loading = true;

      var data = {
        email: this.loginData.email,
        password: this.loginData.password,
      };
      try {
        await this.$auth.loginWith("local", {
          data: data,
        });

        this.$router.push({
          path: "/account",
        });
      } catch (e) {
        this.$toast.error(Object.values(e.response.data.messages).join(" "));
        this.loading = false;
      }
    },

    keyUp() {
      if (window.event.keyCode == 13) {
        if (this.forgotPassword) {
          this.resetPassword();
        } else {
          this.login();
        }
      }
    },

    async resetPassword() {
      this.$v.$touch();
      if (this.$v.recoverPassEmail.$invalid) {
        return;
      }

      const formData = new FormData();
      formData.append("email", this.recoverPassEmail);

      try {
        await this.$axios.$post("/auth/password/forgot", formData);
        //redirect
        this.$toast.success(
          "Email with a link for resetting your password is sent to you."
        );
        this.$refs.backToLoginForm.click();
      } catch (e) {
        this.$toast.error(Object.values(e.response.data.messages).join(" "));
      }
    },

    async register() {
      this.$v.$touch();
      if (this.$v.registerData.$invalid) {
        return;
      }

      const formData = new FormData();
      formData.append("first_name", this.registerData.first_name);
      formData.append("last_name", this.registerData.last_name);
      formData.append("email", this.registerData.email);
      formData.append("password", this.registerData.password);
      formData.append("password_confirmation", this.registerData.password_confirmation);

      try {
        await this.$axios.$post("/auth/register", formData);
        //redirect
        this.$toast.success("You have successfully registered.");
        this.$v.$reset();
        this.$refs.backToLoginFormFromRegister.click();
      } catch (e) {
        this.$toast.error(Object.values(e.response.data.messages).join(" "));
      }
    },
  },
};
</script>
