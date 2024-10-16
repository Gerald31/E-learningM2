const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
  darkMode: "class",
  content: ["./index.html", "./src/**/*.{js,jsx,vue}"],
  theme: {
    extend: {
      screens: {
        'max-767': {'max': '767px'},
        'max-820': {'max': '820px'},
        'max-991': {'max': '991px'},
      },
      zIndex: {
        '999': '999',
        '100': '100',
        '99': '99',
        '9': '9',
        '2': '2',
      },
      spacing: {
        '1/5': '20%',
        '7/10': '70%',
        '5/7': '140%',
      },
      transformOrigin: {
        'top-left-1/3-4': '33% 100%',
        'top-left-1/3-0': '33% 0',
      },
      borderRadius: {
        '4xl': '40px',
      },
      backgroundImage: {
        'welcome-area': "url('@/assets/img/banner-bg.png')",
        'work-process': "url('@/assets/img/work-process-bg.png')",
        'fun-facts': "url('@/assets/img/fun-facts-bg.png')",
        'circle-dec': "url('@/assets/img/circle-dec.png')",
      },
      fontFamily: {
        sans: ["Inter", ...defaultTheme.fontFamily.sans],
      },
      boxShadow: {
        "t-lg":
          "var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), 0 -10px 15px -3px rgba(0, 0, 0, 0.1), 0 -4px 6px -2px rgba(0, 0, 0, 0.05)",
      },
      colors: {
        dark: {
          bg: "#151823",
          "eval-1": "#222738",
          "eval-2": "#2A2F42",
          "eval-3": "#2C3142",
        },
      },
      minHeight: {
        '2/3': '75%',
        '1/2': '50%',
        '1/3': '33%',
        '1/4': '25%',
        '1/5': '20%',
      },
      minWidth: {
        '2/3': '75%',
        '1/2': '50%',
        '1/3': '33%',
        '1/4': '25%',
        '1/5': '20%',
      },
    },
  },
  plugins: [
    require("@tailwindcss/forms"),

    // TODO: use `https://github.com/Kamona-WD/tailwindcss-perspective` package when it support tailwindcss v3.
    require("./tailwindcss-perspective"),
  ],
};
