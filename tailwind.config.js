const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  purge: [
    './resources/views/**/*.blade.php',
    './resources/css/**/*.css',
  ],
  theme: {
    maxWidth: {
      '1/4': '25%',
      '1/2': '50%',
      '3/4': '75%',
    },
    screens: {
          'sm': '640px',
          // => @media (min-width: 640px) { ... }

          'md': '768px',
          // => @media (min-width: 768px) { ... }

          'lg': '1024px',
          // => @media (min-width: 1024px) { ... }

          'xl': '1280px',
          // => @media (min-width: 1280px) { ... }
          'xsl': '1450px',
          'xxl' : '1650px',
          'xxxl': '2200px',
          'xxll':'3000px',
          'xxlll':'3841px',
      },
      aspectRatio: {
          none: 0,
          square: [1, 1],
          "16/10": [16, 10],
          "16/11": [16, 11],
          "16/9": [16, 9],
          "16/8": [16, 8],
          "4/3": [4, 3],
          "3/2": [3 ,2],
          "21/9": [21, 9],
          "21/10": [21, 10],
          "21/11": [21, 11],
          "22/11": [22, 11],
          "21/12": [21, 12],
          "5/2": [5, 2],
          "5/3": [5, 3],
          "5/4": [5, 4],
          "10/7": [10, 7],
          "6/5": [6, 5],
          "7/6": [7, 6],
          "8/7": [8, 7],
          "9/10": [9, 10],
      },
    extend: {
        spacing:{
            '28':'7rem',
            '68': '17rem',
            '72': '18rem',
            '96': '24rem',
            '108': '27rem',
            '120': '30rem',
            '136': '34rem',
            '160': '40rem',
            '200': '50rem',
            '600':'150rem',
            '4k':'600px',
        },
        fontFamily: {
            sans: ['Inter var', ...defaultTheme.fontFamily.sans],
        },
    }
  },
  variants: {
      aspectRatio: ['responsive'],
      borderWidth: ['responsive', 'hover', 'focus'],
      backgroundColor: ['responsive', 'hover', 'focus', 'active', 'group-hover'],
  },
  plugins: [
    require('@tailwindcss/custom-forms'),
      require("tailwindcss-responsive-embed"),
      require("tailwindcss-aspect-ratio"),
  ]
}
