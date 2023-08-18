<template>
  <div id="sc-page-wrapper">
    <div id="sc-page-content">
      <ScCard>
        <ScCardBody>
          <div>Name: {{ user.first_name }} {{ user.last_name }}</div>
          <div class="uk-margin-small-top uk-margin-bottom">Email: {{ user.email }}</div>
          <nuxt-link
            to="account/edit"
            class="sc-button sc-button-default sc-button-outline"
            href="javascript:void(0)"
            @click="$auth.logout()"
          >
            Edit Profile
          </nuxt-link>
          <a
            style="background-color: #c62828; color: white"
            class="sc-button sc-button-default sc-button-outline"
            href="javascript:void(0)"
            @click="$auth.logout()"
          >
            Logout
          </a>
        </ScCardBody>
      </ScCard>
    </div>
  </div>
</template>

<script>
export default {
  async asyncData({ params, $axios }) {
    const user = await $axios.$get("/me").then((res) => res.data);
    return { user };
  },
  data: () => ({
    user: {},
  }),
};
</script>
