<template>
  <div class="uk-flex uk-flex-center uk-flex-middle sc-login-page-wrapper">
    <div class="uk-width-2-3@s uk-width-1-2@m uk-width-1-3@l uk-width-1-4@xl uk-margin-auto-vertical">
      <ScCard>
        <ScCardBody>
          <div class="sc-login-page-logo">
            <img v-rjs="require('~/assets/img/logo.png')" :src="appLogo" alt="">
          </div>
          <div id="sc-login-form" class="sc-toggle-login-register sc-toggle-login-password">
            <div class="sc-login-page-inner">
              <div class="uk-margin-medium">
                <ScInput v-model="password" type="password" name="password" :error-state="$v.password.$error" :validator="$v.password">
                  <label>
                    Password
                  </label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.password.required">
                    Field is required.
                  </li>
                </ul>
              </div>
              <div class="uk-margin-medium">
                <ScInput v-model="confirm_password" type="password" name="confirm_password" :error-state="$v.confirm_password.$error" :validator="$v.confirm_password">
                  <label>
                    Confirm Password
                  </label>
                </ScInput>
                <ul class="sc-vue-errors">
                  <li v-if="!$v.confirm_password.required">
                    Field is required.
                  </li>
                  <li v-if="!$v.confirm_password.sameAs">
                    Passwords must match.
                  </li>
                </ul>
              </div>
              <div class="uk-margin-large-top">
                <a href="javascript:void(0)" class="sc-button sc-button-large sc-button-block sc-button-danger" style="background: #c1b49a" @click.prevent="resetPassword()">
                  Reset Password
                </a>
              </div>
            </div>
          </div>
        </ScCardBody>
      </ScCard>
    </div>
  </div>
</template>

<script>
import ScInput from '~/components/Input'
import { validationMixin } from 'vuelidate'
import { required, sameAs } from 'vuelidate/lib/validators'

export default {
  name: 'ResetPasswordPage',
  components: {
    ScInput
  },
  layout: 'login_page',
  middleware ({ store, redirect }) {
    if (store.state.authenticated) {
      return redirect('/account')
    }
  },
  auth: 'guest',
  mixins: [validationMixin],
  data: () => ({
    password: '',
    confirm_password: ''
  }),
  validations: {
    password: {
        required
    },
    confirm_password: {
        required,
        sameAs: sameAs('password')
    }
},
  head () {
    return {
      'title': 'Reset Password'
    }
  },
  computed: {
    appLogo () {
      return require('~/assets/img/logo.png');
    },
  },
  mounted () {
    this.$axios.$get('/sanctum/csrf-cookie');
    
    if (this.$auth.$state.loggedIn) {
      this.$router.push({
        path: '/'
      })
    }

    window.addEventListener('keyup', this.keyUp);
  },
  beforeDestroy(){
    window.removeEventListener('keyup', this.keyUp);
  },
  methods: {
    keyUp() {
      if (window.event.keyCode == 13) {
        this.resetPassword();
      }
    },
    
    async resetPassword() {
        this.$v.$touch();
        if (this.$v.$invalid) {
            return;
        }

        const formData = new FormData();
        formData.append('password', this.password);
        formData.append('confirm_password', this.confirm_password);

        try {
            await this.$axios.$post('/auth/password/recover/' + this.$route.query.token, formData)
            //redirect
            this.$toast.success('Password changed successfully.');
            
            this.$router.push({
                path: '/login_page'
            })
        } catch (e) {
            this.$toast.error(Object.values(e.response.data.messages).join(' '));
        }
    }
  }
}
</script>
