/** @type {import('tailwindcss').Config} */
export default {
  darkMode: "class",
  content: [
    "./components/**/*.{js,vue,ts}",
    "./layouts/**/*.vue",
    "./pages/**/*.vue",
    "./plugins/**/*.{js,ts}",
    "./app.vue",
    "./error.vue",
  ],
  theme: {
    extend: {
      fontSize: {
        xs: "0.675rem", // 90% of 0.75rem
        sm: "0.7875rem", // 90% of 0.875rem
        base: "0.9rem", // 90% of 1rem
        lg: "1.0125rem", // 90% of 1.125rem
        xl: "1.125rem", // 90% of 1.25rem
        "2xl": "1.35rem", // 90% of 1.5rem
        "3xl": "1.575rem", // 90% of 1.75rem
        "4xl": "1.8rem", // 90% of 2rem
        "5xl": "2.025rem", // 90% of 2.25rem
        "6xl": "2.25rem", // 90% of 2.5rem
      },
    },
  },
  plugins: [],
};
