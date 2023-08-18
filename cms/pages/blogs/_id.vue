<template>
  <div id="sc-page-wrapper">
    <div id="sc-page-content">
      <div class="uk-child-width-1" data-uk-grid>
        <div>
          <ScCard>
            <ScCardHeader separator>
              <div class="uk-flex uk-flex-middle">
                <i class="mdi mdi-plus uk-margin-medium-right sc-icon-24"></i>
                <ScCardTitle> Update Blog </ScCardTitle>
              </div>
            </ScCardHeader>
            <ScCardBody>
              <form class="uk-form-stacked">
                <div class="uk-margin">
                  <ScInput
                    v-model="model.title"
                    :error-state="$v.model.title.$error"
                    :validator="$v.model.title"
                    name="title"
                  >
                    <label class="required">Title</label>
                  </ScInput>
                  <ul class="sc-vue-errors">
                    <li v-if="!$v.model.title.required">Field is required</li>
                  </ul>
                </div>
                <div class="uk-margin">
                  <ScTextarea
                    v-model="model.body"
                    :error-state="$v.model.body.$error"
                    :validator="$v.model.body"
                  >
                    <label class="required">Body:</label>
                  </ScTextarea>

                  <ul class="sc-vue-errors">
                    <li v-if="!$v.model.body.required">Field is required</li>
                  </ul>
                </div>

                <div class="uk-margin">
                  <div class="images__field">
                    <label class="">Blog Image: </label>
                    <div data-uk-form-custom="target: true" class="uk-form-custom">
                      <input type="file" @change="handleImageUpload" ref="image" />
                      <input
                        class="uk-visible@s uk-input uk-form-width-medium"
                        type="text"
                        placeholder="Select images"
                      />
                      <ScInput style="display: none"> </ScInput>
                    </div>
                  </div>
                </div>

                <div class="uk-margin" v-if="img != ''">
                  <img :src="img" alt="" />
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
import { required } from "vuelidate/lib/validators";

if (process.client) {
  require("~/plugins/flatpickr");
}

export default {
  middleware: "",
  name: "FormsExamplesContact",
  components: {
    ScInput,
    ScTextarea,
    PrettyCheck,
    ScProgressCircular,
  },
  mixins: [validationMixin],
  data: () => ({
    img: "",
    model: { title: "", body: "", image: "" },
    loading: false,
  }),
  validations: {
    model: {
      title: {
        required,
      },
      body: {
        required,
      },
    },
  },
  async asyncData({ $axios, app, params }) {
    try {
      const item = await $axios.$get(`blogs/${params.id}`);
      let model = {};
      let img = "";
      model.title = item.data.title;
      model.body = item.data.body;
      if (item.data.image != null) {
        img = item.data.image;
      }

      return {
        model: model,
        img: img,
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
        formData.append(key, this.model[key]);
      }

      try {
        await this.$axios.$post("/blogs/" + this.$route.params.id, formData);
        //redirect
        this.$router.replace("/blogs");
        this.$toast.success("Blog updated successfully.");
      } catch (e) {
        this.loading = false;
        this.$toast.error(Object.values(e.response.data.messages).join(" "));
      }
    },
    handleImageUpload() {
      let file = this.$refs.image.files[0];
      let url = "";
      this.img = URL.createObjectURL(file);
      this.model.image = file;
    },
  },
};
</script>

<style lang="scss">
@import "~scss/vue/_pretty_checkboxes";
</style>
