/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';
import  './vue/vue';

// imports
import * as bootstrap from 'bootstrap'

// jquery libraries
const $ = require('jquery');
window.$ = $;


import DataTable from 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.min.css';
const dataTablesDe = require('datatables.net-plugins/i18n/de-DE.json')
const dataTablesEn = require('datatables.net-plugins/i18n/en-GB.json')

// register basic usability handles
$(document).ready(function () {
  // datatable
  const language = document.documentElement.lang.includes('de') ? dataTablesDe : dataTablesEn;
  new DataTable('.datatable', {
    language,
    pageLength: 10,
    order: []
  });

  // bootstrap
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  // when selecting files, the filename is shown in the input
  $('.custom-file-input').on('change', function () {
    const fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass('selected').html(fileName);
  });

  usability();
});

function usability () {
  // prevent double submit; give user instant feedback
  $('form').on('submit', function () {
    const $form = $(this);
    const $buttons = $('.btn', $form);
    if (!$buttons.hasClass('no-disable')) {
      $buttons.addClass('disabled');
    }
  });

  // force reload on user browser button navigation
  $(window).on('popstate', function () {
    location.reload(true);
  });
}
