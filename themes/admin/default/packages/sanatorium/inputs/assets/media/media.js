/*
 *  mediamanager - v1.0.0
 *  Media Manager for cartalyst/Platform
 *  sanatorium/inputs
 *  http://sanatorium.ninja
 *
 *  Made by Jan Rozklad
 *  Under MIT License
 */
;(function ($, window, document, undefined) {

  "use strict";

  // Create the defaults once
  var mediaManager = "defaultMediaManager",
      defaults     = {
        propertyName: "value"
      };

  // The actual plugin constructor
  function MediaManager(element, options) {
    this.element = element;

    this.settings  = $.extend({}, defaults, options);
    this._defaults = defaults;
    this._name     = mediaManager;
    this.init();
  }

  // Avoid Plugin.prototype conflicts
  $.extend(MediaManager.prototype, {

    // @TODO: move to options
    $loadable     : null,
    currentData   : null,
    sort          : 'created_at',
    filters       : {
      images   : ['image/jpeg', 'image/png', 'image/gif'],
      documents: ['text/plain', 'application/pdf'],
      audio    : ['audio/ogg'],
      video    : ['video/mp4', 'video/ogg']
    },
    mode          : 'single',
    $manager      : null,
    $input        : null,
    loaded        : false,
    selected      : [],
    $statusbar    : null,
    typingTimer   : null,
    typingInterval: 300,
    langs         : {
      messages: {
        deleting      : 'Deleting media file',
        deleted       : 'Media was succesfully deleted',
        deleted_errors: 'There was an error deleting media file',
      }

    },

    init: function ($manager) {

      this.$manager = $(this.element);

      this.cacheSelectors($manager);

      this.addGlobalListeners();

      this.select(this.selected);

    },

    cacheSelectors: function () {

      this.$loadable = this.$manager.find('[data-media-load]');
      this.$sorters = this.$manager.find('[data-media-sort]');
      this.$filters = this.$manager.find('[data-media-filter]');
      this.$sidebar = this.$manager.find('.media-manager-sidebar');
      this.template = this.$manager.data('preview-template');
      this.$search = $(this.$manager.data('search'));
      this.$dropzone = $(this.$manager.data('dropzone'));
      this.mode = ( typeof this.$manager.data('mode') !== 'undefined' ? this.$manager.data('mode') : this.mode );
      this.input_name = this.$manager.data('input-name');
      this.form_group = this.$manager.data('form-group');
      this.$input = $('[name="' + this.$manager.data('input-name') + '"]');
      this.$statusbar = this.$manager.find('.modal-status');
    },

    addGlobalListeners: function () {

      var self = this;

      // On modal display
      this.$manager.on('show.bs.modal', function (event) {

        if (!self.loaded)
          self.loadLists();

      });

      this.listenSort();

      this.listenFilter();

      this.listenSearch();

      this.activateDnd();

    },

    /**
     * Activate Drag and drop Drop function
     */
    activateDnd: function() {

      var self = this;

      FileAPI.event.dnd(this.$dropzone.get(0), function(over) {
        console.log(over);
        if ( over ) {
          self.$dropzone.addClass('over');
        } else {
          self.$dropzone.removeClass('over');
        }

      }, function(files) {

        console.log(files);
        if ( files.length ) {

          // upload files

        }

      });

    },

    /**
     * Search input change
     */
    listenSearch: function () {

      var self = this;

      this.$search.change(function (event) {
        console.log($(this).val());
        self.searchTerm($(this).val());

      });

      this.$search.keyup(function () {
        clearTimeout(self.typingTimer);
        self.typingTimer = setTimeout(function () {

          self.searchTerm(self.$search.val());

        }, self.typingInterval);
      });

    },

    searchTerm: function (term) {

      var self = this;

      if (typeof self.currentData.media === 'undefined')
        return true;

      this.currentData.media = _.filter(self.currentData.all, function (element) {
        return element.name.indexOf(term) !== -1;
      });

      this.showContents();

    },

    /**
     * Filter select box change
     */
    listenFilter: function () {

      var self = this;

      this.$filters.change(function (event) {

        self.beforeLoad();

        var value = $(this).val();

        if (typeof self.filters[value] == 'undefined')
          return false;

        // @todo - make dynamic
        var key = 'mime';

        // On filter
        self.filterData(key, self.filters[value]);

      });

    },

    /**
     * @example filterData('mime', ['image/jpg, image/png'], this.showContents())
     * @param key
     * @param values
     */
    filterData: function (key, values) {

      var self = this;

      if (typeof self.currentData.media === 'undefined')
        return true;

      this.currentData.media = _.filter(self.currentData.all, function (element) {
        return values.indexOf(element[key]) !== -1;
      });

      this.showContents();

    },

    listenSort: function () {

      var self = this;

      this.$sorters.change(function (event) {

        self.beforeLoad();

        var value = $(this).val();

        // On sort change
        self.sortData(value);

      });

    },

    sortData: function (sortby) {

      var self = this;

      if (typeof self.currentData.media === 'undefined')
        return true;

      this.currentData.media = _.sortBy(self.currentData.media, function (item) {
        return item[sortby];
      });

      this.showContents();

    },

    loadLists: function () {

      var self = this;

      this.$loadable.each(function () {

        self.$el = $(this);

        var url = self.$el.data('media-load');

        $.ajax({
          type: 'GET',
          url : url
        }).success(function (data) {

          self.currentData = {
            media: data,
            count: data.length,
            all  : data,
          };

          self.showContents();

          self.loaded = true;

        });

      });

    },

    showContents: function () {

      var template = $('#' + this.template).html(),
          html     = _.template(template)(this.currentData);

      this.$el.html(html);

      this.afterLoad();

    },

    beforeLoad: function () {

      this.$el.fadeOut(250);

    },

    afterLoad: function () {

      this.$el.fadeIn(250);

      this.activateThumbnails();

      var self = this;

      this.$input.each(function () {

        self.$el.find('.media-manager-preview[data-id="' + $(this).val() + '"]').addClass('selected');

      });

    },

    activateThumbnails: function () {

      var self = this;

      this.$el.find('.media-manager-preview:not(.activated)').click(function (event) {

        self.clickMedia($(this));

      }).addClass('activated');

    },

    clickMedia: function ($media) {

      if (this.mode == 'single') {

        this.deselectAll();

        this.select($media.data('id'));

        $media.toggleClass('selected');

      } else {

        $(this.form_group).find('.media-manager-input').remove();

        $media.toggleClass('selected');

        this.selected = this.$el.find('.media-manager-preview.selected').map(function () {
          return $(this).data('id');
        }).get();

        this.select(this.selected);

      }

      this.showSidebar($media.data());

    },

    select: function (ids) {

      if (typeof ids === 'string' || typeof ids === 'number') {

        this.selectOne(ids);

      } else {

        this.selectMore(ids);

      }

    },

    selectOne: function (id) {

      this.$input.val(id);

    },

    selectMore: function (ids) {

      for (var key in ids) {

        var id = ids[key];
        $('<input type="hidden" class="media-manager-input">').attr({
          name : this.input_name,
          value: id,
          type : 'hidden'
        }).appendTo(this.form_group);

      }

    },

    deselectAll: function () {

      this.$el.find('.media-manager-preview.selected').removeClass('selected');

    },

    /**
     * Display given data in sidebar
     * @param data
     */
    showSidebar: function (data) {

      var template = $('#media-sidebar-template').html(),
          html     = _.template(template)(data);

      this.$sidebar.html(html);

      this.activateSidebar();

    },

    /**
     * Activate sidebar buttons and functionalities
     */
    activateSidebar: function () {

      var self = this;

      // Init "Copy to clipboard" on [data-clipboard-target] element
      new Clipboard('[data-clipboard-target]');

      // Delete image
      this.$sidebar.find('[data-delete]').click(function (event) {

        event.preventDefault();

        var url = $(this).attr('href');

        self.status(self.langs.messages.deleting);

        $.ajax({
          type: 'DELETE',
          url : url
        }).success(function (data) {
          self.status(self.langs.messages.deleted, 'success');

          $('.media-manager-preview[data-id="' + data.id + '"]').remove();
        }).error(function (data) {
          self.status(self.langs.messages.deleted_error, 'danger');
        });

      });

    },

    /**
     * Show message in status bar
     * @param string msg Text to show
     * @param string type Bootstrap alert type (info|success|danger|warning|primary)
     */
    status: function (msg, type) {

      if (typeof type == 'undefined')
        type = 'info';

      var icon = 'fa fa-refresh';

      switch (type) {

        case 'danger':
          icon = 'fa fa-check';
          break;

        case 'success':
          icon = 'fa fa-times';
          break;

      }

      this.$statusbar.html('<p class="text-' + type + ' media-manager-status-text"><i class="' + icon + '"></i> ' + msg + '</p>');

    }

  });

  $.fn[mediaManager] = function (options) {
    return this.each(function () {
      if (!$.data(this, "plugin_" + mediaManager)) {
        $.data(this, "plugin_" +
            mediaManager, new MediaManager(this, options));
      }
    });
  };

})(jQuery, window, document);


// Init
$(function () {
  $('.media-manager').defaultMediaManager();
});

// url http://stackoverflow.com/questions/10420352/converting-file-size-in-bytes-to-human-readable
function humanFileSize(bytes, si) {
  var thresh = si ? 1000 : 1024;
  if (Math.abs(bytes) < thresh) {
    return bytes + ' B';
  }
  var units = si
      ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
      : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
  var u     = -1;
  do {
    bytes /= thresh;
    ++u;
  } while (Math.abs(bytes) >= thresh && u < units.length - 1);
  return bytes.toFixed(1) + ' ' + units[u];
}