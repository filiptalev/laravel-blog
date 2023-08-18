/* options */

const mode = "spa"; // universal/spa
const serveFromSubFolder = false;

/* options end */

const pkg = require("./package");
const path = require("path");

let dist = "dist";

module.exports = {
  ssr: mode === "universal",

  server: {
    port: process.env.PORT,
    host: process.env.HOST,
    serverUrl: process.env.API_URL,
  },

  env: {
    serverUrl: process.env.API_URL,
    wwwUrl: process.env.WWW_URL,
  },

  /*
   ** Headers of the page
   */
  head: {
    htmlAttrs: {
      lang: "en",
    },
    title: "Blogs",
    meta: [
      { charset: "utf-8" },
      {
        name: "viewport",
        content: "width=device-width, initial-scale=1",
      },
      {
        hid: "description",
        name: "description",
        content: pkg.description,
      },
    ],
    script: [
      {
        src:
          (process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "" : "/" + dist) +
          "/vendor/uikit.min.js",
      },
    ],
    link: [
      {
        rel: "icon",
        type: "image/x-icon",
        href:
          (process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "" : "/" + dist) +
          "/favicon.ico",
      },
      {
        rel: "preload",
        href:
          (process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "" : "/" + dist) +
          "/vendor/uikit.min.js",
        as: "script",
      },
      {
        rel: "preload",
        href:
          (process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "" : "/" + dist) +
          "/fonts/roboto_base64.css",
        as: "style",
      },
      {
        rel: "preload",
        href:
          (process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "" : "/" + dist) +
          "/fonts/sourceCodePro_base64.css",
        as: "style",
      },
      {
        rel: "preload",
        href:
          (process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "" : "/" + dist) +
          "/fonts/mdi/css/materialdesignicons.css",
        as: "style",
      },
      /// fonts
      {
        rel: "stylesheet",
        href:
          (process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "" : "/" + dist) +
          "/fonts/roboto_base64.css",
      },
      {
        rel: "stylesheet",
        href:
          (process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "" : "/" + dist) +
          "/fonts/sourceCodePro_base64.css",
      },
      {
        rel: "stylesheet",
        href:
          (process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "" : "/" + dist) +
          "/fonts/mdi/css/materialdesignicons.css",
      },
    ],
  },
  /*
   ** Customize the progress-bar
   */
  loading: "~/components/LoadingBar.vue",
  /*
   ** Customize the loading-indicator
   ** only in spa mode
   */
  loadingIndicator: {
    color: "#00838f",
    background: "white",
  },
  /*
   ** Global CSS
   */
  css: ["uikit/dist/css/uikit.css"],
  /*
   ** Plugins to load before mounting the App
   */
  plugins: [
    { src: "~/plugins/components.global.js" },
    { src: "~/plugins/directives.client.js" },
    { src: "~/plugins/filters.js" },
    { src: "~/plugins/mixins.client.js" },
    { src: "~/plugins/waves.client.js" },
    { src: "~/plugins/retina.client.js" },
    { src: "~/plugins/vueVisible.client.js" },
    { src: "~/plugins/vue-signature-pad.client.js" },
  ],
  router: {
    middleware: ["redirect", "auth"],
    base: process.env.NODE_ENV !== "production" || !serveFromSubFolder ? "/" : "/" + dist,
  },
  modules: [
    "@nuxtjs/axios",
    "@nuxtjs/toast",
    "@nuxtjs/auth-next",
    "@nuxtjs/dotenv",
    "@nuxtjs/google-analytics",
    [
      "nuxt-i18n",
      {
        defaultLocale: "en",
        locales: [
          {
            code: "en",
            file: "en-US.js",
            name: "English",
          },
          {
            code: "es",
            file: "es-ES.js",
            name: "Español",
          },
          {
            code: "fr",
            file: "fr-FR.js",
            name: "Français",
          },
        ],
        lazy: true,
        langDir: "lang/",
        strategy: "no_prefix",
        vueI18n: {
          fallbackLocale: "en",
          silentTranslationWarn: true,
        },
        vuex: {
          syncLocale: true,
        },
      },
    ],
    // '@nuxtjs/webpack-profile'
  ],
  googleAnalytics: {
    id: mode === "universal" ? "UA-136690566-3" : "UA-136690566-4",
    // disable for development
    dev: process.env.NODE_ENV !== "production",
  },

  // Axios module configuration (https://go.nuxtjs.dev/config-axios)
  axios: {
    baseUrl: process.env.API_URL,
    credentials: true,
  },
  auth: {
    strategies: {
      local: {
        token: {
          property: "data.token",
          global: true,
          maxAge: 28800,
          // required: true,
          // type: 'Bearer'
        },
        user: {
          property: "data",
          // autoFetch: true
        },
        endpoints: {
          login: {
            url: "/auth/login",
            method: "POST",
          },
          user: {
            url: "/me",
          },
          logout: {
            url: "/auth/logout",
          },
        },
      },
    },
    redirect: {
      home: false,
      login: "/login_page",
      logout: "/login_page",
      callback: "/login_page",
    },
  },
  toast: {
    position: "top-right",
    register: [
      // Register custom toasts
      {
        name: "my-error",
        message: "Oops...Something went wrong",
        options: {
          type: "error",
        },
      },
    ],
    duration: 3000,
    containerClass: "custom-position",
  },

  /*
   ** The server Property
   ** https://nuxtjs.org/api/configuration-server
   */
  // server: {
  // 	port: 3104, // default: 3000
  // 	timing: false,
  // 	https: {
  // 		key: fs.readFileSync(path.resolve(__dirname, '.https/server.key')),
  // 		cert: fs.readFileSync(path.resolve(__dirname, '.https/server.crt'))
  // 	}
  // },
  generate: {
    dir: dist,
  },
  /*
   ** Build configuration
   */
  build: {
    // analyze: true,
    progress: true,
    babel: {
      plugins: ["@babel/plugin-syntax-dynamic-import", "@babel/plugin-transform-spread"],
      ignore: ["node_modules", "assets/js/vendor"],
    },
    extend(config, ctx) {
      transpile: [/^gmap-vue($|\/)/];
      // if (ctx.isDev && ctx.isClient) {
      // 	config.module.rules.push(
      // 		// Run ESLint on save
      // 		{
      // 			enforce: 'pre',
      // 			test: /\.(js|vue)$/,
      // 			loader: 'eslint-loader',
      // 			exclude: /(node_modules)/
      // 		}
      // 	);
      // }
      // aliases
      config.resolve.alias["scss"] = path.resolve(__dirname, "./assets/scss");
      // adjust path when serving app from sub-folder
      if (!ctx.isDev && serveFromSubFolder) {
        config.output.publicPath = "/" + dist + "/_nuxt/";
      }
      return config;
    },
  },
};
