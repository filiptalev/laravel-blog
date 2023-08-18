<template>
  <div id="sc-page-wrapper">
    <div id="sc-page-content">
      <h1 class="uk-margin">All Blogs</h1>

      <div class="uk-child-width-1-2@l uk-grid" data-uk-grid>
        <div class="" v-for="blog in blogs" :key="blog.id">
          <div class="uk-open">
            <h2 class="title">
              <span>{{ blog.title }}</span>
              <span>{{ blog.user.first_name }} {{ blog.user.last_name }}</span>
            </h2>

            <div class="uk-accordion-content">
              <div class="uk-grid-medium" data-uk-grid>
                <div class="uk-width-1-3@m">
                  <ScPhoto
                    :src="blog.image"
                    size="md"
                    class="uk-border-rounded uk-box-shadow"
                  ></ScPhoto>
                </div>
                <div class="uk-width-expand@m">
                  {{ blog.body }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import ScPhoto from "~/components/Photo";
export default {
  name: "Dashboard",
  components: {
    ScPhoto,
  },
  data: () => ({
    blogs: {},
  }),
  computed: {},
  async asyncData({ $axios, app, params }) {
    try {
      const item = await $axios.$get(`/blogs`);

      return {
        blogs: item.data,
      };
    } catch (e) {
      this.$toast.error(Object.values(e.response.data.messages).join(" "));
    }
  },
  created() {},
  mounted() {},
  beforeDestroy() {},
  methods: {},
};
</script>

<style lang="scss">
@import "~scss/vue/pretty_checkboxes";
@import "~scss/plugins/jqvmap.scss";
.title {
  font-size: 24px;
  border: 1px solid blue;
  border-radius: 42px;
  padding: 0.25rem 0.5rem;
  display: flex;
  justify-content: space-between;
}
</style>
