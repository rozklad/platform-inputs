/**
 * Part of the Platform Attributes extension.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Cartalyst PSL License.
 *
 * This source file is subject to the Cartalyst PSL License that is
 * bundled with this package in the LICENSE file.
 *
 * @package    Platform Attributes extension
 * @version    4.0.0
 * @author     Cartalyst LLC
 * @license    Cartalyst PSL
 * @copyright  (c) 2011-2016, Cartalyst LLC
 * @link       http://cartalyst.com
 */

var Extension;

;(function(window, document, $, undefined)
{

  'use strict';

  Extension = Extension || {
        Form: {},
      };

  // Initialize functions
  Extension.Form.init = function()
  {
    Extension.Form
        .listeners()
        .selectize()
        .sortable()
        .optionsChanger()
    ;
  };

  // Add Listeners
  Extension.Form.listeners = function()
  {
    Platform.Cache.$body
        .on('change', '#type', Extension.Form.optionsChanger)
        .on('click', '[data-option-add]', Extension.Form.addOption)
        .on('click', '[data-option-remove]', Extension.Form.removeOption)
    ;

    return this;
  };

  // Initialize Selectize
  Extension.Form.selectize = function()
  {
    $('select:not([data-selectize-disabled])').selectize({
      create: true, sortField: 'text',
    });

    return this;
  };

  // Initialize Sortable
  Extension.Form.sortable = function()
  {
    // Sortable rows
    $('.options').sortable({
      handle: '[data-option-move]',
      containerSelector: '.options',
      itemSelector: 'li',
      nested: true,
      distance: 10,
      placeholder: '<li class="placeholder">Drop here</li>',
    });

    return this;
  };

  //
  Extension.Form.optionsChanger = function()
  {
    if ($('#type').find(':selected').data('allow-options'))
    {
      $('[data-options]').removeClass('hide');
      $('[data-no-options]').addClass('hide');
    }
    else
    {
      $('[data-options]').addClass('hide');
      $('[data-no-options]').removeClass('hide');
    }

    return this;
  };

  //
  Extension.Form.addOption = function(event)
  {
    event.preventDefault();

    var totalRows = $('.options li').length;

    var option = { label : null, value : null, id : totalRows + 1 };

    Extension.Form.attachOption(option);

    return this;
  };

  // Remove an option
  Extension.Form.removeOption = function(event)
  {
    event.preventDefault();

    if ($('.options li').length >= 2) $(this).closest('li').remove();

    return this;
  };

  // Sets the given options
  Extension.Form.setOptions = function(options)
  {
    _.each(options, function(label, value)
    {
      var option = { label : label, value : value, id : value };

      Extension.Form.attachOption(option);
    });

    // Add an empty option
    Extension.Form.attachOption({ label : null, value : null, id : 'newwww' });
  };

  //
  Extension.Form.attachOption = function(data)
  {
    var template = _.template($('[data-option-template]').html());

    $('.options').append(
        template(data)
    );
  };

  // Job done, lets run.
  Extension.Form.init();

})(window, document, jQuery);
