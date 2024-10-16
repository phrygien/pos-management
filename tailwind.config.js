import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
		'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
		 './storage/framework/views/*.php',
		 './resources/views/**/*.blade.php',
		 "./vendor/robsontenorio/mary/src/View/Components/**/*.php",
         './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php'
	],

    theme: {
        extend: {
            fontFamily: {
                //sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                sans: ['Fira Sans', 'sans-serif'],
                //sans: ['Josefin Sans', 'sans-serif'],
            },
        },
    },

    plugins: [
		//forms,
		require("daisyui")
	],
};
