import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  base: "/inf513/grupo12sc/proyecto2/public/build/",
  // base: "/estetica-definity-laser/public/build/",
  plugins: [
    laravel({
      input: ["resources/css/app.css", "resources/js/app.js"],
      refresh: true,
    }),
    vue(),
  ],
  // server: {
  //     host: true,
  // },
});
