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
      all      : [],
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
        deleting      : '<i class="fa fa-refresh"></i> Deleting media file',
        deleted       : '<i class="fa fa-times"></i> Media was succesfully deleted',
        deleted_errors: '<i class="fa fa-times"></i> There was an error deleting media file',
        uploading     : '<i class="fa fa-refresh"></i> Uploading media file',
        uploaded      : '<i class="fa fa-check"></i> Media file was succesfully uploaded',
        upload_error  : '<i class="fa fa-times"></i> There was an error uploading media file'
      }

    },
    urls          : {
      upload: null
    },
    icon: {
      def:   'fa fa-file-o',
      image: 'fa fa-file-pdf-o',
      audio: 'fa fa-file-movie-o',
      video: 'fa fa-file-movie-o',
    },
    token         : null,
    thumbnailSize : 300,

    init: function ($manager) {

      this.$manager = $(this.element);

      this.cacheSelectors($manager);

      this.addGlobalListeners();

      this.select(this.selected);

    },

    cacheSelectors: function () {

      this.$loadable   = this.$manager.find('[data-media-load]');
      this.$sorters    = this.$manager.find('[data-media-sort]');
      this.$filters    = this.$manager.find('[data-media-filter]');
      this.$dropzone   = this.$manager.find('[data-media-dropzone]');
      this.$sidebar    = this.$manager.find('.media-manager-sidebar');
      this.template    = this.$manager.data('preview-template');
      this.$search     = $(this.$manager.data('search'));
      this.mode        = ( typeof this.$manager.data('mode') !== 'undefined' ? this.$manager.data('mode') : this.mode );
      this.input_name  = this.$manager.data('input-name');
      this.form_group  = this.$manager.data('form-group');
      this.$input      = $('[name="' + this.input_name + '"]');
      this.$statusbar  = this.$manager.find('.modal-status');
      this.urls.upload = this.$manager.data('upload-url');
      this.token       = this.$manager.data('token');
      this.controls    = {
        $upload: this.$manager.find('[data-tab-control-upload]'),
        $library: this.$manager.find('[data-tab-control-library]')
      };
      this.$preview    = $( this.$manager.data('preview') );
      this.$external   = $('[data-external-control="' + this.input_name + '"]');

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

      this.activateExternal();

    },

    /**
     * Activate external controls
     */
    activateExternal: function() {

      var self = this;

      this.$external.click(function(event){
          event.preventDefault();

          var type = $(this).data('external-type');

          switch( type ) {
            case 'delete':
              self.deselectAll();
            break;
          }

      });

    },

    /**
     * Activate Drag and drop Drop function
     */
    activateDnd: function () {

      var self = this;

      this.$dropzone.each(function(){

        var $dropzone = $(this);

        FileAPI.event.dnd($dropzone.get(0), function (over) {

          if (over) {
            $dropzone.addClass('over');
          } else {
            $dropzone.removeClass('over');
          }

        }, function (files) {

          $('a[href="'+ self.controls.$library.attr('href') +'"]').tab('show');

          if (files.length) {

            self.uploadFiles(files);

          }

        });

      });

    },

    /**
     * Triggered from activateDnd above
     * @param files
     */
    uploadFiles: function (files) {

      var self = this;

      for (var key in files) {

        var upload = files[key];

        self.showUploading(upload);

        self.uploadFile(upload);

      }

    },

    getIdByFile: function(file) {

      return 'file-'+FileAPI.uid(file);

    },

    showUploading: function(file) {

      var self = this;

      var id = this.getIdByFile(file);

      // Add loading thumbs in all manager list
      // later this might need to be revised (as the shown data might be different)
      this.$manager.find('.media-manager-list').prepend(
          tmpl($('#file-ejs').html(), { file: file, icon: self.icon })
      );

      if( /^image/.test(file.type) ){
        FileAPI.Image(file).preview(self.thumbnailSize).rotate('auto').get(function (err, img){
          
          $('#' + id).find('.media-manager-preview-uploader').replaceWith(img);
          
        });
      }

    },

    uploadFile: function (upload) {

      var self  = this;

      self.status(self.langs.messages.uploading);

      var xhr = FileAPI.upload({
        url         : self.urls.upload,
        files       : {file: upload},
        data        : {_token: self.token},
        // On upload complete
        complete    : function (err, xhr) {
          if (!err) {
            var result = xhr.responseText;
            self.loadLists();

            console.log(result);

            self.status(self.langs.messages.uploaded, 'success');
          } else {

            self.status(self.langs.messages.upload_error, 'danger');
          }
        },
        // On upload progress
        progress    : function (evt, file, xhr, options) {
          var pr = evt.loaded / evt.total * 100;

          var id = self.getIdByFile(file);

          $('#' + id).find('.media-manager-progress__bar').css({
            'width' : pr + '%'
          });
          
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

      if (typeof values === 'undefined' || values.length == 0 ) {
        // Show all
        this.currentData.media = self.currentData.all;
      } else {
        // Filter by specified key for specified values
        this.currentData.media = _.filter(self.currentData.all, function (element) {
          return values.indexOf(element[key]) !== -1;
        });
      }

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

    sortData: function (sortby, reverse) {

      var self = this;

      if (typeof self.currentData.media === 'undefined')
        return true;
      
      if (sortby.indexOf(':') !== -1 ) {
        var parts = sortby.split(':');
        sortby = parts[0];
        if ( parts[1] == 'desc' ) {
          reverse = true;
        } else {
          reverse = false;
        }
      } else {
        reverse = false;
      }

      if ( !reverse ) {
        this.currentData.media = _.sortBy(self.currentData.media, function (item) {
          return item[sortby];
        });
      } else {
        this.currentData.media = _.sortBy(self.currentData.media, function (item) {
          return item[sortby];
        }).reverse();
      }

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

        switch( true ) {

          case event.shiftKey:
              self.clickShiftMedia($(this));
            break;

          case event.ctrlKey:
          case event.metaKey: // Command key (⌘) / Windows key
              self.clickCtrlMedia($(this));
            break;

          default:
            break;

        }

        self.clickMedia($(this));

      }).addClass('activated');

    },

    clickShiftMedia: function($media) {

      console.log('shift', this.mode);

      if (this.mode == 'single') {

        $media.closest('.media-manager-preview.selected').nextUntil($media, '.media-manager-preview').addClass('manipulated');

      } else {

        $media.closest('.media-manager-preview.selected').nextUntil($media, '.media-manager-preview').addClass('selected');

      }

    },

    clickCtrlMedia: function($media) {

      console.log('ctrl', this.mode);

      if (this.mode == 'single') {

        $media.addClass('manipulated');

      } else {

        $media.addClass('selected');

      }

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

      this.$input.val('');
      this.$el.find('.media-manager-preview.selected').removeClass('selected');

    },

    demanipulateAll: function() {

      this.$el.find('.media-manager-preview.mainpulated').removeClass('mainpulated');

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

      this.$statusbar.html('<p class="text-' + type + ' media-manager-status-text">' + msg + '</p>');

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


// Simple JavaScript Templating
// John Resig - http://ejohn.org/ - MIT Licensed
(function (){
  var cache = {};

  this.tmpl = function tmpl(str, data){
    // Figure out if we're getting a template, or if we need to
    // load the template - and be sure to cache the result.
    var fn = !/\W/.test(str) ?
        cache[str] = cache[str] ||
            tmpl(document.getElementById(str).innerHTML) :

        // Generate a reusable function that will serve as a template
        // generator (and which will be cached).
        new Function("obj",
            "var p=[],print=function(){p.push.apply(p,arguments);};" +

            // Introduce the data as local variables using with(){}
            "with(obj){p.push('" +

            // Convert the template into pure JavaScript
            str
                .replace(/[\r\t\n]/g, " ")
                .split("<%").join("\t")
                .replace(/((^|%>)[^\t]*)'/g, "$1\r")
                .replace(/\t=(.*?)%>/g, "',$1,'")
                .split("\t").join("');")
                .split("%>").join("p.push('")
                .split("\r").join("\\'")
            + "');}return p.join('');");

    // Provide some basic currying to the user
    return data ? fn(data) : fn;
  };
})();