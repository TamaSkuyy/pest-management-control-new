/**
 * Config
 * -------------------------------------------------------------------------------------
 * ! IMPORTANT: Make sure you clear the browser local storage In order to see the config changes in the template.
 * ! To clear local storage: (https://www.leadshook.com/help/how-to-clear-local-storage-in-google-chrome-browser/).
 */

'use strict';

// JS global variables
window.config = {
  colors: {
    primary: '#696cff',
    secondary: '#8592a3',
    success: '#71dd37',
    info: '#03c3ec',
    warning: '#ffab00',
    danger: '#ff3e1d',
    dark: '#233446',
    black: '#22303e',
    white: '#fff',
    cardColor: '#fff',
    bodyBg: '#f5f5f9',
    bodyColor: '#646E78',
    headingColor: '#384551',
    textMuted: '#a7acb2',
    borderColor: '#e4e6e8'
  }
};

let assetsPath = document.documentElement.getAttribute('data-assets-path'),
  templateName = document.documentElement.getAttribute('data-template'),
  rtlSupport = false; // set true for rtl support (rtl + ltr), false for ltr only.

window.templateName = templateName;

/**
 * TemplateCustomizer
 * ! You must use(include) template-customizer.js to use TemplateCustomizer settings
 * -----------------------------------------------------------------------------------------------
 */

// To use more themes, just push it to THEMES object.

/* TemplateCustomizer.THEMES.push({
  name: 'theme-raspberry',
  title: 'Raspberry'
}); */

// To add more languages, just push it to LANGUAGES object.
/*
TemplateCustomizer.LANGUAGES.fr = { ... };
*/

/**
 * TemplateCustomizer settings
 * -------------------------------------------------------------------------------------
 * cssPath: Core CSS file path
 * themesPath: Theme CSS file path
 * displayCustomizer: true(Show customizer), false(Hide customizer)
 * lang: To set default language, Add more langues and set default. Fallback language is 'en'
 * controls: [ 'rtl','style','layoutType','showDropdownOnHover','layoutNavbarFixed','layoutFooterFixed','themes'] | Show/Hide customizer controls
 * defaultTheme: 0(Default), 1(Semi Dark), 2(Bordered)
 * defaultStyle: 'light', 'dark' (Mode)
 * defaultTextDir: 'ltr', 'rtl' (rtlSupport must be true for rtl mode)
 * defaultLayoutType: 'static', 'fixed'
 * defaultMenuCollapsed: true, false
 * defaultNavbarFixed: true, false
 * defaultFooterFixed: true, false
 * defaultShowDropdownOnHover : true, false (for horizontal layout only)
 */

if (typeof TemplateCustomizer !== 'undefined') {
  window.templateCustomizer = new TemplateCustomizer({
    cssPath: assetsPath + 'vendor/css' + (rtlSupport ? '/rtl' : '') + '/',
    themesPath: assetsPath + 'vendor/css' + (rtlSupport ? '/rtl' : '') + '/',
    displayCustomizer: false,
    // lang: 'fr',
    // defaultTheme: 2,
    // defaultStyle: 'light',
    // defaultTextDir: 'ltr',
    // defaultLayoutType: 'fixed',
    // defaultMenuCollapsed: true,
    // defaultNavbarFixed: true,
    // defaultFooterFixed: false
    defaultShowDropdownOnHover: true
    // controls: [
    //   'rtl',
    //   'style',
    //   'layoutType',
    //   'showDropdownOnHover',
    //   'layoutNavbarFixed',
    //   'layoutFooterFixed',
    //   'themes'
    // ],
  });
}
