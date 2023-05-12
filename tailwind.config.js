/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{php,html}", "./js.*.{js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        "back-color": "#DBDBDB",
      },
    },

    fontFamily: {
      body: ["ＭＳ Ｐゴシック"],
    },
  },
  plugins: [],
};
